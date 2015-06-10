<?php namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\GeneralRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CPasswordRequest;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Booking;
use Carbon\Carbon;
use App\Installtion;
use App\Http\Requests\InstallationRequest;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller {

	public function postLogin(LoginRequest $request) {
        if (Auth::attempt(['phone' => $request->get('phone'), 'password' => $request->get('password')]))
        {
            $bookingList = Booking::where('user_id', Auth::user()->id)->where('is_paid', false)->where('time_slot','<', Carbon::now())->get();
            if (count($bookingList) >= intval(env('SCAM_BOOKING'))) {
                return response(json_encode(['message' => 'Account has been ban due to multiple scam booking. Please contact our manager for more information.']), 400);
            }
            return response(Auth::user());
        }
        return response(json_encode(['message' => 'Invalid credentials']), 400);
    }


    public function postForgotPassword(ResetPasswordRequest $request) {
        $user = User::where('phone', $request->get('phone'))->first();
        if (!$user) {
            //error
            return response(json_encode(['message' => 'user not found']), 404);
        }

        $newPassword = $this->generateRandomString(6);

        $user->password = bcrypt($newPassword);
        $user->update();

        Mail::send('emails.forgotPassword', ['password' => $newPassword, 'phone' => $user->phone], function($message) use ($user)
        {
            $message->to($user->email);
        });
        return response(json_encode(['message' => 'Sent email to '.$user->email]));
    }

    public function postChangePassword(ChangePasswordRequest $request) {
        if (!Hash::check($request->get('current_password'), $request->auth->password)){
            return response(json_encode(['message' => 'Invalid current password']), 400);
        }
        $request->auth->password = bcrypt($request->get('password'));
        $request->auth->save();
        return response($request->auth);
    }


    /*
     * Post to change user profile
     */
    public function changeProfile(GeneralRequest $request) {
        $data = [];

        if ($request->has('first_name')) {
            $data['first_name'] = $request->get('first_name');
        }

        if ($request->has('last_name')) {
            $data['last_name'] = $request->get('last_name');
        }

        if (count($data) > 0) {
            $request->auth->update($data);
        }
        return response($request->auth);
    }

    public function getCurrentUser(GeneralRequest $request) {
        $bookingList = Booking::where('user_id', $request->auth->id)->where('is_paid', false)->where('time_slot','<', Carbon::now())->get();
        if (count($bookingList) >= intval(env('SCAM_BOOKING'))) {
            return response(json_encode(['message' => 'Account has been ban due to multiple scam booking. Please contact our manager for more information.']), 400);
        }
        return response($request->auth);
    }

    public function postRegister(RegisterRequest $request) {
        $user = new User();
        $user->phone = $request->get('phone');
        $user->password = bcrypt($request->get('password'));
        $user->email = $request->get('email');
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->type = $request->get('customer');
        $user->is_blocked = 0;
        $user->api_token = $this->generateRandomString(45);
        $user->save();
        return response($user);
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


    public function installation(InstallationRequest $request)
    {
        $token = $request->get('token');
        $installation = Installtion::with('user')->where('token', $token)->first();
        if (!$installation) {
            $installation = new Installtion();
            $installation->token = $token;
            $installation->user_id = $request->get('user_id');
            $installation->save();
        } else {
            $installation->token = $token;
            $installation->user_id = $request->get('user_id');
            $installation->save();
        }

        $installation = Installtion::with('user')->where('token', $token)->first();
        return $installation;
    }



}
