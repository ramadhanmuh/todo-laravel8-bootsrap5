<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
        $start_date = $this->start_date;
        $end_date = $this->end_date;
        
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:16777215'],
            'start_date' => ['nullable', 'string', 'date'],
            'start_time' => [
                'nullable', 'string', 'date_format:H:i',
                function ($attribute, $value, $fail) use ($start_date) {
                    if (empty($start_date) && !empty($value)) {
                        $fail('The Start Date is required.');
                    }
                },
            ],
            'end_date' => [
                'nullable', 'string', 'date',
                function ($attribute, $value, $fail) use ($start_date) {
                    if (empty($start_date) && !empty($value)) {
                        $fail('The Start Date is required.');
                    }
                },
            ],
            'end_time' => [
                'nullable', 'string', 'date_format:H:i',
                function ($attribute, $value, $fail) use ($end_date) {
                    if (empty($end_date) && !empty($value)) {
                        $fail('The End Date is required.');
                    }
                },
            ]
        ];
    }
}
