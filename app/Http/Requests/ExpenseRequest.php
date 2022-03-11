<?php

namespace App\Http\Requests;

use App\Http\Repositories\CategoryRepository;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function __construct(CategoryRepository $categoryRepository, array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->categoryRepository = $categoryRepository;
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
        $sometimes = 'sometimes|filled';

        $data = [
            'description' => 'required|string|max:191',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category' => [
                'sometimes',
                'filled',
                'integer',
                $this->categoryIsValid(),
            ],
        ];

        if ($this->method() == 'PUT') {
            $data['description'] = $sometimes . '|string|max:191';
            $data['amount'] = $sometimes . '|numeric';
            $data['date'] = $sometimes . '|date';
        }

        return $data;
    }

    private function categoryIsValid()
    {
        if (!$this->category) {
            return;
        }

        $categoryExist = $this->categoryRepository->find($this->category);;

        return function ($attribute, $value, $fail) use ($categoryExist) {
            if (!$categoryExist) {
                return $fail(__('validation.custom.category.invalid'));
            }
        };
    }
}
