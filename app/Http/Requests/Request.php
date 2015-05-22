<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest {

	//

    public function authorize()
    {
        return false;
    }

    public function forbiddenResponse()
    {
        return response(json_encode(['message' => 'Unauthorized']), 403);
    }

}
