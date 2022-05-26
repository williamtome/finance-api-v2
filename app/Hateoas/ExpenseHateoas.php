<?php

namespace App\Hateoas;

use App\Models\Expense;
use GDebrauwer\Hateoas\Link;
use GDebrauwer\Hateoas\Traits\CreatesLinks;

class ExpenseHateoas
{
    use CreatesLinks;

    /**
     * Get the HATEOAS link to view the expense.
     *
     * @param \App\Models\Expense $expense
     *
     * @return null|Link
     */
    public function self(Expense $expense): ?Link
    {
        return $this->link('expense.show', ['expense' => $expense]);
    }

    public function update(Expense $expense): ?Link
    {
        return $this->link('expense.update', ['expense' => $expense]);
    }

    public function delete(Expense $expense): ?Link
    {
        return $this->link('expense.destroy', ['expense' => $expense]);
    }
}
