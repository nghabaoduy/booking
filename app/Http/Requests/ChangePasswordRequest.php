<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;

class ChangePasswordRequest extends Request {

    public $auth = null;

    public function authorize()
    {
        if ($this->header('Authorization') == "" || !$this->header('Authorization')) {
            return false;
        } else {
            $this->auth = User::where('api_token', $this->header('Authorization'))->first();

            if ($this->auth) {
                return true;
            }
        }
        return false;
    }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
            'current_password'=> 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
		];
	}

}
