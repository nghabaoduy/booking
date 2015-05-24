<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateBookingRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			//
            'time_slot' => 'required',
            'type' => 'required',
            'total_order' => 'required',
            'food_orders' => 'required'
		];
	}

}
