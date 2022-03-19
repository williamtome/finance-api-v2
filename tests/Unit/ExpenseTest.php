<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\User;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    private array $header;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->header = ['Accept' => 'application/json'];
        $this->user = User::factory()->create();
    }

    public function test_should_forbid_an_unauthenticated_user_to_create_an_expense()
    {
        $data = [
            'description' => 'expense test',
            'amount' => 10,
            'date' => '2022-02-28',
        ];

        $this->json('POST', 'api/expense', $data, $this->header)
            ->assertUnauthorized();
    }

    public function test_should_allow_an_authenticated_user_to_create_an_expense()
    {
        $this->seed();

        $data = [
            'description' => 'expense test',
            'amount' => 10,
            'date' => '2022-02-28',
        ];

        $this->actingAs($this->user)
            ->json('POST', 'api/expense', $data, $this->header)
            ->assertOk();
    }

    public function test_should_create_an_expense_with_a_valid_category()
    {
        $this->seed();

        $data = [
            'description' => 'expense test 2',
            'amount' => 20,
            'date' => '2022-02-22',
            'category' => 1,
        ];

        $this->actingAs($this->user)
            ->json('POST', 'api/expense', $data, $this->header)
            ->assertOk();
    }

    public function test_should_fail_validation_when_creating_an_expense_with_an_invalid_category()
    {
        $this->seed();

        $data = [
            'description' => 'expense test 3',
            'amount' => 30,
            'date' => '2022-03-01',
            'category' => 95,
        ];

        $this->actingAs($this->user)
            ->json('POST', 'api/expense', $data, $this->header)
            ->assertUnprocessable();
    }

    public function test_should_check_if_the_expense_was_saved_in_database_sucessfully()
    {
        $this->seed();

        $data = [
            'description' => 'expense test 4',
            'amount' => 30,
            'date' => '2022-03-01',
            'category' => 2,
        ];

        $this->actingAs($this->user)
            ->json('POST', 'api/expense', $data, $this->header)
            ->assertOk();

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
        Expense::factory()->count(3)->create();

        $this->actingAs($this->user)
            ->get(route('expense.index'), $this->header)
            ->assertOk();

        $this->assertDatabaseCount('expenses', 3);
    }

    public function test_should_show_an_expense_created()
    {
        $expense = Expense::factory()->createOne();

        $this->actingAs($this->user)->get(
            route('expense.show', $expense),
            $this->header
        )->assertOk();

        $this->assertEquals('Expense test', $expense->description);
        $this->assertEquals('99.95', $expense->amount);
        $this->assertEquals('2022-01-01', $expense->date);
        $this->assertEquals('8', $expense->category_id);
    }

    public function test_should_update_an_expense()
    {
        $this->seed();

        $expense = Expense::factory()->createOne();

        $this->actingAs($this->user)
            ->json('PUT', route('expense.update', $expense), [
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

        $expense = Expense::factory()->createOne();

        $this->actingAs($this->user)->delete(
            route('expense.destroy', $expense)
        );

        $this->assertDatabaseMissing('expenses', [
            'id' => $expense->id,
        ]);
    }
}
