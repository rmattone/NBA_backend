<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePersonRequest extends FormRequest
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
            'personId' => ['required', 'int'],
            'name' => ['nullable', 'string'],
            'birth' => ['nullable', 'string', 'max:10'],
            'death' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'file'],
            'birthPlace' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge(['personId' => (int) $this->route('personId')]);
    }
}
