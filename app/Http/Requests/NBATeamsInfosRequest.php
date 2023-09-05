<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NBATeamsInfosRequest extends FormRequest
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
            'teamId' => ['required', 'int']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['teamId' => (int) $this->route('teamId')]);
    }
}
