<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
        $user = DB::table('users')->select('password')
                                    ->where('id', '=', session('userAuth')->id)
                                    ->where('role', '=', 'User')
                                    ->first();

        return [
            'old_password' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The Old Password is incorrect.');
                    }
                }
            ],
            'password' => [
                'required', 'string', 'max:255', 'confirmed'
            ]
        ];
    }
}
