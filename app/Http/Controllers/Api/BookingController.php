<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Booking;
use App\FoodOrder;
use App\Http\Requests\GeneralRequest;
use App\Http\Requests\CreateBookingRequest;

class BookingController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(GeneralRequest $request)
	{
		//
        $data = Booking::with('foodOrders')->where('user_id', $request->auth->id)->get();
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
                'total_price' => $food['total_price']
            ];
            FoodOrder::create($foodData);
        }
        return response(null, 204);
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

}
