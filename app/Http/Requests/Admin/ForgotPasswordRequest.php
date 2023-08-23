<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ForgotPasswordRequest extends FormRequest
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
            'email' => [
                'required', 'email', 'max:191',
                function ($attribute, $value, $fail) {
                    $user = DB::table('users')->select('id')
                                                ->where('role', '=', 'Administrator')
                                                ->where('email', '=', $value)
                                                ->first();

                    if (empty($user)) {
                        $fail('The '.$attribute.' is not found.');
                    }
                }
            ]
        ];
    }
}
