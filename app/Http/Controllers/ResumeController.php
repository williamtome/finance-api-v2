<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ExpenseRepository;
use App\Http\Repositories\RevenueRepository;
use App\Http\Requests\ResumeRequest;
use Illuminate\Http\Response;

class ResumeController extends Controller
{
    private RevenueRepository $revenueRepository;

    private ExpenseRepository $expenseRepository;

    public function __construct(
        RevenueRepository $revenueRepository,
        ExpenseRepository $expenseRepository
    ) {
        $this->revenueRepository = $revenueRepository;
        $this->expenseRepository = $expenseRepository;
    }

    public function show(ResumeRequest $request)
    {
        $year = $request->route('year');
        $month = $request->route('month');

        $filterPatternDate = $this->filterDate($month, $year);

        $revenues = $this->revenueRepository->getByDate($filterPatternDate);
        $expenses = $this->expenseRepository->getByDate($filterPatternDate);

        $totalOfRevenues = $revenues->sum('amount');
        $totalOfExpenses = $expenses->sum('amount');
        $finalBalance = $totalOfRevenues - $totalOfExpenses;

        return new Response([
            'year' => (int) $year,
            'month' => (int) $month,
            'total_revenues_month' => $totalOfRevenues,
            'total_expenses_month' => $totalOfExpenses,
            'final_balance' => $finalBalance,
        ]);
    }

    private function filterDate(string $month, string $year): string
    {
        $month = $month <= 9 ? 0 . $month : $month;

        return $year . '-' . $month . '-%';
    }
}
