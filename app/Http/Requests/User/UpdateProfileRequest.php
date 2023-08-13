<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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
        $user = DB::table('users')->select('username', 'email')
                                    ->where('id', '=', session('userAuth')->id)
                                    ->where('role', '=', 'User')
                                    ->first();

        return [
            'name' => [
                'required', 'string', 'max:255'
            ],
            'username' => [
                'required', 'string', 'max:191',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value !== $user->username) {
                        $user = DB::table('users')->select('id')
                                                    ->where('role', '=', 'User')
                                                    ->where('username', '=', $value)
                                                    ->first();
    
                        if (empty($user)) {
                            $fail('The '.$attribute.' has been used.');
                        }
                    }
                }
            ],
            'email' => [
                'required', 'string', 'max:191',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value !== $user->email) {
                        $user = DB::table('users')->select('id')
                                                    ->where('role', '=', 'User')
                                                    ->where('email', '=', $value)
                                                    ->first();
    
                        if (empty($user)) {
                            $fail('The '.$attribute.' has been used.');
                        }
                    }
                }
            ]
        ];
    }
}
