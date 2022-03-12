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
            'amount' => 'required|numeric',
            'date' => 'required|date',
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
}
