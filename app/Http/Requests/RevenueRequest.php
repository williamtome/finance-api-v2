<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RevenueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $sometimes = 'sometimes|filled';

        $rules = [
            'description' => 'required|string|max:191',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date_format:Y-m-d'
        ];

        if ($this->method() === 'PUT') {
            $rules['description'] = $sometimes . '|string|max:191';
            $rules['amount'] = $sometimes . '|numeric|min:1';
            $rules['date'] = $sometimes . '|date_format:Y-m-d';
        }

        return $rules;
    }
}
