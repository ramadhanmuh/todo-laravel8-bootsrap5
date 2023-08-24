<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
        $start_time = $this->start_time;

        return [
            'id' => ['required', 'string', 'uuid', 'unique:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:16777215'],
            'string_start_date' => [
                'required_with_all:string_start_time,string_end_date,string_end_time',
                'date'
            ],
            'string_start_time' => [
                'nullable', 'date_format:H:i'
            ],
            'string_end_date' => [
                'required_with_all:string_start_time', 'date'
            ],
            'string_end_time' => [
                'nullable', 'date_format:H:i'
            ],
            'start_time' => [
                'nullable', 'numeric', 'max:146011735807', 'min:0'
            ],
            'end_time' => [
                'required_with_all:start_time', 'numeric', 'max:146011735807', 'min:0',
                'gte:start_time',
                // function ($attribute, $value, $fail) use ($start_time) {
                //     if (empty($start_time) && !empty($value)) {
                //         $fail('The ' . $attribute . ' is required.');
                //     }
                // },
            ]
        ];
    }
}
