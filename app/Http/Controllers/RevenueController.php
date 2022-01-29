<?php

namespace App\Http\Controllers;

use App\Models\Revenue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RevenueController extends Controller
{
    public function index(Request $request): Response
    {
        if ($request->has('descricao')) {
            $revenue = Revenue::where('description', $request->descricao)->first();
            return new Response($revenue);
        }

        return new Response(Revenue::all());
    }

    public function store(Request $request): void
    {
        $revenue = new Revenue();
        $revenue->create($request->all());
    }

    public function show($id): Response
    {
        $revenue = Revenue::findOrFail($id);

        return new Response($revenue);
    }

    public function update(Request $request, $id): void
    {
        $revenue = Revenue::findOrFail($id);
        $revenue->update($request->all());
    }

    public function destroy($id): void
    {
        Revenue::destroy($id);
    }
}
