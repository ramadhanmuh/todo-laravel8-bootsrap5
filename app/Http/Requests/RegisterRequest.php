<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
            'id' => [
                'required', 'uuid', 'unique:users,id'
            ],
            'name' => [
                'required', 'string', 'max:255'
            ],
            'username' => [
                'required', 'string', 'alpha_dash', 'max:191', 'unique:users,username'
            ],
            'email' => [
                'required', 'email', 'max:191', 'unique:users,email'
            ],
            'password' => [
                'required', 'string', 'max:255', 'confirmed'
            ]
        ];
    }
}
