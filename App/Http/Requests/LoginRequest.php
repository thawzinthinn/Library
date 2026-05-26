<?php

namespace App\Http\Requests;

class LoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
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