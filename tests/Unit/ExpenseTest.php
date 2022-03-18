<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    private array $header;

    protected function setUp(): void
    {
        $this->header = ['Accept' => 'application/json'];
        parent::setUp();
    }

    public function test_should_forbid_an_unauthenticated_user_to_create_an_expense()
    {
        $data = [
            'description' => 'expense test',
            'amount' => 10,
            'date' => '2022-02-28',
        ];

        $response = $this->json('POST', 'api/expense', $data, $this->header);

        $response->assertUnauthorized();
    }

    public function test_should_allow_an_authenticated_user_to_create_an_expense()
    {
        $this->seed();

        $user = User::factory()->create();

        $data = [
            'description' => 'expense test',
            'amount' => 10,
            'date' => '2022-02-28',
        ];

        $response = $this->actingAs($user)
            ->json('POST', 'api/expense', $data, $this->header);

        $response->assertOk();
    }

    public function test_should_create_an_expense_with_a_valid_category()
    {
        $this->seed();

        $user = User::factory()->create();

        $data = [
            'description' => 'expense test 2',
            'amount' => 20,
            'date' => '2022-02-22',
            'category' => 1,
        ];

        $response = $this->actingAs($user)
            ->json('POST', 'api/expense', $data, $this->header);

        $response->assertOk();
    }

    public function test_should_fail_validation_when_creating_an_expense_with_an_invalid_category()
    {
        $this->seed();

        $user = User::factory()->create();

        $data = [
            'description' => 'expense test 3',
            'amount' => 30,
            'date' => '2022-03-01',
            'category' => 95,
        ];

        $response = $this->actingAs($user)
            ->json('POST', 'api/expense', $data, $this->header);

        $response->assertUnprocessable();
    }

    public function test_should_check_if_the_expense_was_saved_in_database_sucessfully()
    {
        $this->seed();

        $user = User::factory()->create();

        $data = [
            'description' => 'expense test 4',
            'amount' => 30,
            'date' => '2022-03-01',
            'category' => 2,
        ];

        $response = $this->actingAs($user)
            ->json('POST', 'api/expense', $data, $this->header);

        $response->assertOk();
        $this->assertDatabaseHas('expenses', [
            'id' => "1",
            'description' => 'expense test 4',
            'amount' => "30",
            'date' => '2022-03-01',
            'category_id' => "2",
        ]);
    }

    public function test_should_show_all_the_expenses_created()
    {
        $user = User::factory()->createOne();
        Expense::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->get(route('expense.index'), $this->header);

        $response->assertOk();
        $this->assertDatabaseCount('expenses', 3);
    }

    public function test_should_show_an_expense_created()
    {
        $user = User::factory()->createOne();
        $expense = Expense::factory()->createOne();

        $response = $this->actingAs($user)
            ->get(
                route('expense.show', $expense),
                $this->header
            );

        $response->assertOk();
        $this->assertEquals('Expense test', $expense->description);
        $this->assertEquals('99.95', $expense->amount);
        $this->assertEquals('2022-01-01', $expense->date);
        $this->assertEquals('8', $expense->category_id);
    }

    public function test_should_update_an_expense()
    {
        $this->seed();

        $user = User::factory()->create();
        $expense = Expense::factory()->createOne();

        $this->actingAs($user)->json('PUT', route('expense.update', $expense), [
            'description' => 'Update expense test',
            'amount' => 40,
            'date' => '2022-03-01',
        ], ['Content-Type' => 'application/json']);

        $expense->refresh();

        $this->assertEquals('Update expense test', $expense->description);
        $this->assertEquals('40', $expense->amount);
        $this->assertEquals('2022-03-01', $expense->date);
        $this->assertEquals('8', $expense->category_id);
    }

    public function test_should_delete_an_expense()
    {
        $this->seed();

        $user = User::factory()->createOne();
        $expense = Expense::factory()->createOne();

        $this->actingAs($user)->delete(
            route('expense.destroy', $expense)
        );

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
    }
}
