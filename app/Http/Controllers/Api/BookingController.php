<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Booking;
use App\FoodOrder;
use App\Http\Requests\GeneralRequest;
use App\Http\Requests\CreateBookingRequest;
use Carbon\Carbon;
use Davibennun\LaravelPushNotification\Facades\PushNotification;

class BookingController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(GeneralRequest $request)
	{
		//
        $data = Booking::with('foodOrders');
        if ($request->has('is_paid')) {
            $data = $data->where('is_paid', $request->get('is_paid'));
        }
        if ($request->has('is_rescheduled')) {
            $data = $data->where('is_rescheduled', $request->get('is_rescheduled'));
        }
        if ($request->has('is_confirmed')) {
            $data = $data->where('is_confirmed', $request->get('is_confirmed'));
        }
        $data = $data->where('user_id', $request->auth->id)->orderBy('time_slot', 'DESC')->get();
        return response($data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateBookingRequest $request)
	{
		//


        $timeSlotDate = new Carbon($request->get('time_slot'));
        $now = Carbon::now();


        if ($now->diffInHours($timeSlotDate) == 0) {
            return response(json_encode(['message' => 'booking time must be after '. $now->addHour(1)->toDateTimeString()]), 400);
        }



        $data = [
            'is_confirmed' => 0,
            'time_slot' => $request->get('time_slot'),
            'is_rescheduled' => 0,
            'user_id' => $request->auth->id,
            'booking_ref' => $this->generateRandomString(10),
            'is_paid' => 0,
            'total_order' => $request->get('total_order'),
            'type' => $request->get('type')
        ];

        $booking = Booking::create($data);

        $foodOrders = $request->get('food_orders');


        foreach ($foodOrders as $food) {
            $foodData = [
                'booking_id' => $booking->id,
                'name' => $food['name'],
                'quantity' => $food['quantity'],
                'total_price' => $food['total_price'],
                'code' => $food['code'],
                'type' => $food['type'] ? $food['type'] : 'N/A'
            ];
            FoodOrder::create($foodData);
        }
        $booking->foodOrders;
        return response($booking, 200);
	}

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id, GeneralRequest $request)
	{
		//
        $data = Booking::with('foodOrders')->where('user_id', $request->auth->id)->where('id', $id)->first();
        if (!$data) {
            return response(json_encode(['message' => 'booking not found']), 404);
        }
        return response($data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, GeneralRequest $request)
	{
		//
        $data = Booking::with('foodOrders')->where('user_id', $request->auth->id)->where('id', $id)->first();
        if (!$data) {
            return response(json_encode(['message' => 'booking not found']), 404);
        }

        if (!boolval($data->is_rescheduled) && boolval($request->get('is_rescheduled'))) {
            $timeSlotDate = new Carbon($request->get('time_slot'));
            $now = Carbon::now();
            if ($now->diffInHours($timeSlotDate) <= 0) {
                return response(json_encode(['message' => 'reschedule time must be after '. $now->addHour(1)->toDateTimeString()]), 400);
            }
        }
        $data->update($request->all());
        return response($data);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


    public function test() {

        $data = Booking::with('foodOrders', 'user.installationList')
            ->whereBetween('time_slot', [Carbon::now()->toDateTimeString(), Carbon::now()->addHour(1)->toDateTimeString()])
            ->where('is_paid', false)
            ->where('is_confirmed', false)->get();


        foreach ($data as $booking) {
            foreach ($booking->user->installationList as $installation) {
                PushNotification::app('appNameIOS')
                    ->to($installation->token)
                    ->send('You have up coming booking at '.$booking->time_slot.'.Please check your booking list and confirm it');
            }
        }
        return response($data);
    }

}
