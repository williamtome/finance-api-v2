<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ExpenseRepository;
use App\Http\Repositories\RevenueRepository;
use App\Http\Requests\ResumeRequest;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;

class ResumeController extends Controller
{
    use ApiResponser;

    private RevenueRepository $revenueRepository;

    private ExpenseRepository $expenseRepository;

    public function __construct(
        RevenueRepository $revenueRepository,
        ExpenseRepository $expenseRepository
    ) {
        $this->revenueRepository = $revenueRepository;
        $this->expenseRepository = $expenseRepository;
    }

    public function show(ResumeRequest $request): JsonResponse
    {
        $year = $request->route('year');
        $month = $request->route('month');

        $filterPatternDate = $this->filterDate($month, $year);

        $revenues = $this->revenueRepository->getByDate($filterPatternDate);
        $expenses = $this->expenseRepository->getByDate($filterPatternDate);
        $totalExpensesByCategories = $this->expenseRepository->getByDateAndCategory($filterPatternDate);

        $totalOfRevenues = $revenues->sum('amount');
        $totalOfExpenses = $expenses->sum('amount');
        $finalBalance = $totalOfRevenues - $totalOfExpenses;

        return $this->success([
            'ano' => (int) $year,
            'mes' => (int) $month,
            'total_de_receitas_mes' => $totalOfRevenues,
            'total_de_despesas_mes' => $totalOfExpenses,
            'saldo_final' => $finalBalance,
            'total_de_receitas_por_categoria' => $totalExpensesByCategories
        ]);
    }

    private function filterDate(string $month, string $year): string
    {
        $month = $month <= 9 ? 0 . $month : $month;

        return $year . '-' . $month . '-%';
    }
}
