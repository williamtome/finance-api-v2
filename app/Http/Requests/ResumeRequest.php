<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResumeRequest extends FormRequest
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
            'year' => [
                'string',
                $this->validateYear(),
            ],
            'month' => [
                'string',
                $this->validateMonth(),
            ],
        ];
    }

    private function validateYear()
    {
        $incorrectYear = strtotime($this->year) === false || $this->year < 1900;
        $validYear = $this->year >= 1900 && $this->year <= now()->year;

        if ($incorrectYear && !$validYear) {
            abort(404, 'O ano informado está inválido!');
        }
    }

    private function validateMonth()
    {
        $monthIsInRange = (int) $this->month >= 1 && (int) $this->month <= 12;

        if ($monthIsInRange === false) {
            abort(404, 'O mês informado está inválido!');
        }
    }
}
