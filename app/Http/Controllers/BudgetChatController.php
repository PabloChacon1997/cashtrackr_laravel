<?php

namespace App\Http\Controllers;

use App\Ai\Agents\BudgetAsistant;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetChatController extends Controller
{
    public function store(Request $request, Budget $budget) 
    {
        $messages = $request->input('messages', []);
        $lastMessages = collect($messages)->last();

        $prompt =collect(data_get($lastMessages, 'parts', []))
            ->where('type', 'text')
            ->pluck('text')
            ->implode(' ')
            ?: data_get($lastMessages,'content','');
        $agent = new BudgetAsistant;

        $agent->stream($prompt, provider: '', model: '');
        $agent->budgetId = $budget->id;
        $agent->hasCategories = $budget->isGeneral();
        if ($budget->isGoal()) {
            $agent->budgetContext = "Este presupuesto es de tipo Meta/Objetivo llamado '{$budget->name}' con un monto total de \${$budget->amount}. Los gastos NO tienen categorías, solo nombre y monto.";
        } else {
            $agent->budgetContext = "Este presupuesto es de tipo General llamado '{$budget->name}' con un monto total de \${$budget->amount}. Los gastos tienen nombre, monto y categoría.";
        }

        return $agent
            ->stream($prompt,
                provider: 'openrouter',
                model: 'nvidia/nemotron-3-super-120b-a12b:free',
                // model: 'google/gemma-4-26b-a4b-it:free',
                // model: 'nvidia/nemotron-3-ultra-550b-a55b:free',
                // model: 'deepseek/deepseek-v4-flash-0731:free',
                )->usingVercelDataProtocol();
    }
}
