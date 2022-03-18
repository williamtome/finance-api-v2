<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RevenueTest extends TestCase
{
    private array $header;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->header = ['Accept' => 'application/json'];
        $this->user = User::factory()->create();
    }

    public function test_should_forbid_an_unauthenticated_user_to_create_a_revenue()
    {
        $data = [
            'description' => 'revenue test',
            'amount' => 1,
            'date' => '2022-01-02'
        ];

        $this->json('POST','/api/revenue', $data, $this->header)
            ->assertUnauthorized();
    }

    public function test_should_allow_an_authenticated_user_to_create_a_revenue()
    {
        $data = [
            'description' => 'revenue test',
            'amount' => 1,
            'date' => '2022-01-02'
        ];

        $this->actingAs($this->user)
            ->json('POST', '/api/revenue', $data, $this->header)
            ->assertOk();
    }

    public function test_should_fail_validation_when_creating_revenue_without_description()
    {
        $data = [
            'amount' => 1,
            'date' => '2022-01-02'
        ];

        $this->actingAs($this->user)
            ->json('POST', '/api/revenue', $data, $this->header)
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }

    public function test_should_create_a_revenue_successfully()
    {
        $data = [
            'description' => 'revenue test 3',
            'amount' => 20,
            'date' => '2022-01-02',
        ];

        $this->actingAs($this->user)
            ->json('POST', '/api/revenue', $data, $this->header)
            ->assertOk();

        $this->assertDatabaseHas('revenues', [
            'id' => 1,
            'description' => 'revenue test 3',
            'amount' => 20,
            'date' => '2022-01-02',
        ]);
    }
}
