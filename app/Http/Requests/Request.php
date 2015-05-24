<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

abstract class Request extends FormRequest {

    public $auth = null;

    public function authorize()
    {
        dd($this->header('Authorization'));
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

    public function forbiddenResponse()
    {
        return response(json_encode(['message' => 'Unauthorized']), 403);
    }

    public function response(array $errors)
    {
        return response(json_encode($errors), 422);
    }

}
