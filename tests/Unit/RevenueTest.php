<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RevenueTest extends TestCase
{
    use RefreshDatabase;

    private array $header;

    protected function setUp(): void
    {
        $this->header = ['Accept' => 'application/json'];
        parent::setUp();
    }

    public function test_should_forbid_an_unauthenticated_user_to_create_a_revenue()
    {
        $data = [
            'description' => 'revenue test',
            'amount' => 1,
            'date' => '2022-01-02'
        ];

        $response = $this->json('POST','/api/revenue', $data, $this->header);

        $response->assertUnauthorized();
    }

    public function test_should_allow_an_authenticated_user_to_create_a_revenue()
    {
        $user = User::factory()->create();
        $data = [
            'description' => 'revenue test',
            'amount' => 1,
            'date' => '2022-01-02'
        ];

        $response = $this->actingAs($user)
            ->json('POST', '/api/revenue', $data, $this->header);

        $response->assertOk();
    }

    public function test_should_fail_validation_when_creating_revenue_without_description()
    {
        $user = User::factory()->create();
        $data = [
            'amount' => 1,
            'date' => '2022-01-02'
        ];

        $response = $this->actingAs($user)
            ->json('POST', '/api/revenue', $data, $this->header);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }

    public function test_should_create_a_revenue_successfully()
    {
        $user = User::factory()->create();
        $data = [
            'description' => 'revenue test 3',
            'amount' => 20,
            'date' => '2022-01-02',
        ];

        $response = $this->actingAs($user)
            ->json('POST', '/api/revenue', $data, $this->header);

        $response->assertOk();
        $this->assertDatabaseHas('revenues', [
            'id' => 1,
            'description' => 'revenue test 3',
            'amount' => 20,
            'date' => '2022-01-02',
        ]);
    }
}
