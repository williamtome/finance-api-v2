<?php

namespace Tests\Unit;

use App\Http\Requests\RevenueRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RevenueRequestTest extends TestCase
{
    public function test_should_not_authorize_request_if_the_user_is_not_logged_in()
    {
        Auth::shouldReceive('check')->once()->andReturn(false);

        $request = new RevenueRequest();

        $this->assertFalse($request->authorize());
    }

    public function test_should_authorize_request_if_the_user_is_logged_in()
    {
        Auth::shouldReceive('check')->once()->andReturn(true);

        $request = new RevenueRequest();

        $this->assertTrue($request->authorize());
    }

    public function test_should_contain_all_the_expected_validation_rules()
    {
        $request = new RevenueRequest();

        $rules = [
            'description' => 'required|string|max:191',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ];

        $this->assertEquals($rules, $request->rules());
    }

    public function test_should_fail_validation_if_description_field_is_missing()
    {
        $request = new RevenueRequest();
        $request->headers->set(
            'Accept',
            'application/json'
        );

        $payload = [
            'amount' => 10,
            'date' => '2022-01-02',
        ];

        $validator = Validator::make($payload, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertContains('description', $validator->errors()->keys());
    }

    public function test_should_fail_validation_if_description_is_empty()
    {
        $request = new RevenueRequest();
        $request->headers->set(
            'Accept',
            'application/json'
        );

        $payload = [
            'description' => '',
            'amount' => 10,
            'date' => '2022-01-02',
        ];

        $validator = Validator::make($payload, $request->rules());

        $this->assertFalse($validator->passes());
        $this->assertEmpty($payload['description']);
    }
}
