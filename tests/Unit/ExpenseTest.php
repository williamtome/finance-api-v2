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

    public function testShouldForbidAnUnauthenticatedUserToCreateAExpense()
    {
        $data = [
            'description' => 'expense test',
            'amount' => 10,
            'date' => '2022-02-28',
        ];

        $response = $this->json('POST', 'api/expense', $data, $this->header);

        $response->assertUnauthorized();
    }

    public function testShouldAllowAnAuthenticatedUserToCreateAExpense()
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

    public function testShouldCreateAExpenseWithAValidCategory()
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

    public function testShouldFailValidationWhenCreatingAExpenseWithAInvalidCategory()
    {
        $this->seed();

        $user = User::factory()->create();

        $data = [
            'description' => 'expense test 3',
            'amount' => 30,
            'date' => '2022-03-01',
            'category' => 1651651,
        ];

        $response = $this->actingAs($user)
            ->json('POST', 'api/expense', $data, $this->header);

        $response->assertUnprocessable();
    }

    public function testShouldCheckIfTheExpenseWasSavedInDatabaseSucessfully()
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

    public function testShouldShowAllTheExpensesCreated()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('api/expense', $this->header);

        $response->assertOk();
    }

    public function testShouldUpdateAExpense()
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
}
