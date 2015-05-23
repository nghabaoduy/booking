<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegisterRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			//
            'phone' => 'required|unique:user,phone',
            'email' => 'required|unique:user,email',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'first_name' => 'required',
            'last_name' => 'required'
		];
	}

}
