<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateProfileRequest extends FormRequest
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
        $user = DB::table('users')->select('username', 'email', 'password')
                                    ->where('id', '=', session('ownerAuth')->id)
                                    ->where('role', '=', 'Owner')
                                    ->first();

        return [
            'name' => [
                'required', 'string', 'max:255'
            ],
            'username' => [
                'required', 'string', 'max:191', 'alpha_dash',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value !== $user->username) {
                        $user = DB::table('users')->select('id')
                                                    ->where('username', '=', $value)
                                                    ->first();
    
                        if (!empty($user)) {
                            $fail('The '.$attribute.' has been used.');
                        }
                    }
                }
            ],
            'email' => [
                'required', 'email', 'max:191',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value !== $user->email) {
                        $user = DB::table('users')->select('id')
                                                    ->where('email', '=', $value)
                                                    ->first();
    
                        if (!empty($user)) {
                            $fail('The '.$attribute.' has been used.');
                        }
                    }
                }
            ],
            'password' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('The '.$attribute.' is wrong.');
                    }
                }
            ]
        ];
    }
}
