<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NBAPlayerDetailsRequest extends FormRequest
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
            'playerId' => ['required', 'int'],
            'opponentTeamId' => ['nullable', 'int']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['playerId' => (int) $this->route('playerId')]);
    }
}
