<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ExpenseRequest extends FormRequest
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

        $data = [
            'description' => 'required|string|max:191',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date|date_format:Y-m-d',
            'category' => [
                'sometimes',
                'filled',
                'integer',
                $this->categoryIsValid(),
            ],
        ];

        if ($this->method() == 'PUT') {
            $data['description'] = $sometimes . '|string|max:191';
            $data['amount'] = $sometimes . '|numeric|min:1';
            $data['date'] = $sometimes . '|date|date_format:Y-m-d';
        }

        return $data;
    }

    private function categoryIsValid()
    {
        if (!$this->category) {
            return;
        }

        // criar um teste validando isso!
        $categoryExist = Category::find($this->category);

        return function ($attribute, $value, $fail) use ($categoryExist) {
            if (!$categoryExist) {
                return $fail(__('validation.custom.category.invalid'));
            }
        };
    }
}
