<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ExpenseRepository;
use App\Http\Repositories\RevenueRepository;
use App\Http\Requests\ResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Traits\FormatterDateTrait;

class ResumeController extends Controller
{
    use FormatterDateTrait;

    private RevenueRepository $revenueRepository;
    private ExpenseRepository $expenseRepository;

    public function __construct(
        RevenueRepository $revenueRepository,
        ExpenseRepository $expenseRepository
    ) {
        $this->revenueRepository = $revenueRepository;
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * @throws \Exception
     */
    public function show(ResumeRequest $request): ResumeResource
    {
        $year = $request->route('year');
        $month = $request->route('month');

        $revenues = $this->revenueRepository->getByDate($year, $month);
        $expenses = $this->expenseRepository->getByDate($year, $month);
        $totalExpensesByCategories = $this->expenseRepository->getByDateAndCategory($year, $month);

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
}
