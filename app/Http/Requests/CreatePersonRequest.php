<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePersonRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'birth' => ['nullable','string', 'max:10'],
            'death' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'file'],
            'birthPlace' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ];
    }
}
