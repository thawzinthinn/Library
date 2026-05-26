<?php

namespace App\Http\Requests;

class UserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required' => true,
                'min' => 3
            ],
            'email' => [
                'required' => true,
                'email' => true,
                'unique' => 'users'
            ],
            'password' => [
                'required' => true,
                'min' => 6
            ]
        ];
    }
}