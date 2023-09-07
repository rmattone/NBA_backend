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
            'teamId' => ['required', 'int'],
            'startDate' => ['nullable', 'date'],
            'endDate' => ['nullable', 'date'],
            'nLastGames' => ['nullable', 'int'],
            'opponentTeamId' => ['nullable', 'int'],
            'host' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(['teamId' => (int) $this->route('teamId')]);
    }
}
