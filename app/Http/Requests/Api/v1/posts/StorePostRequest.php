<?php

namespace App\Http\Requests\Api\v1\posts;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|required'
        ];
    }
}
