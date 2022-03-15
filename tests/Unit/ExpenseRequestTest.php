<?php

namespace Tests\Unit;

use App\Http\Requests\ExpenseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ExpenseRequestTest extends TestCase
{
    private ExpenseRequest $request;

    protected function setUp(): void
    {
        $this->request = new ExpenseRequest();

        parent::setUp();
    }

    public function test_should_authorize_request_if_the_user_is_not_logged_in()
    {
        Auth::shouldReceive('check')->once()->andReturnFalse();

        $this->assertFalse($this->request->authorize());
    }

    public function test_should_authorize_request_if_the_user_is_logged_in()
    {
        Auth::shouldReceive('check')->once()->andReturnTrue();

        $this->assertTrue($this->request->authorize());
    }

    public function test_should_contain_all_the_expected_validation_rules()
    {
        $rules = [
            'description' => 'required|string|max:191',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date|date_format:Y-m-d',
            'category' => [
                'sometimes',
                'filled',
                'integer',
                null
            ],
        ];

        $this->assertEquals($rules, $this->request->rules());
    }

    public function test_should_fail_validation_if_description_is_empty()
    {
        $this->request->headers->set(
            'Accept',
            'application/json'
        );

        $payload = [
            'description' => '',
            'amount' => 10,
            'date' => '2022-01-02',
        ];

        $validator = Validator::make($payload, $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertEmpty($payload['description']);
    }

    public function test_should_fail_validation_if_amount_field_is_empty_or_zero_value()
    {
        $this->request->headers->set(
            'Accept', 'application/json'
        );

        $payload = [
            'description' => 'Pão de queijo',
            'amount' => 0,
            'date' => '2022-03-11',
        ];

        $validator = Validator::make($payload, $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertEmpty($payload['amount']);
    }

    public function test_should_fail_validation_if_date_field_is_empty()
    {
        $this->request->headers->set(
            'Accept', 'application/json'
        );

        $payload = [
            'description' => 'Bolo de chocolate',
            'amount' => 5.59,
            'date' => '',
        ];

        $validator = Validator::make($payload, $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertEmpty($payload['date']);
    }

    public function test_should_fail_validation_if_date_is_incorrect_format()
    {
        $this->request->headers->set(
            'Accept', 'application/json'
        );

        $payload = [
            'description' => 'Torta de bolacha 200gr',
            'amount' => 12.75,
            'date' => '12/03/2022',
        ];

        $validator = Validator::make($payload, $this->request->rules());

        $this->assertFalse($validator->passes());
        $this->assertStringNotMatchesFormat('Y-m-d', $payload['date']);
    }
}
