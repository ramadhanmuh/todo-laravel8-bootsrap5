<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateUserRequest extends FormRequest
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
        $id = $this->segment(count($this->segments()));

        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'unique:users,username,' . $id
            ],
            'email' => [
                'required', 'string', 'unique:users,email,' . $id
            ],
            'role' => ['required', 'in:Administrator,User'],
            'password' => ['nullable', 'string', 'max:255', 'confirmed']
        ];
    }
}
