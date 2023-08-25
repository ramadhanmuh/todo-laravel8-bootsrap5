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
                'nullable', 'date'
            ],
            'string_start_time' => [
                'nullable', 'date_format:H:i'
            ],
            'string_end_date' => [
                'nullable', 'date'
            ],
            'string_end_time' => [
                'nullable', 'date_format:H:i'
            ],
            'start_time' => [
                'nullable'
            ],
            'end_time' => [
                'nullable'
            ]
        ];
    }
}
