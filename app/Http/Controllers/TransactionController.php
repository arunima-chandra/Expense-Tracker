<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request, Account $account)
    {
        $this->authorize('view', $account);

        return TransactionResource::collection($account->transactions);
    }

    public function store(StoreTransactionRequest $request, Account $account)
    {
        $this->authorize('view', $account);

        $transaction = $account->transactions()->create($request->validated());

        Log::info('Transaction added', [
            'transaction_id' => $transaction->id,
            'account_id' => $account->id,
            'type' => $transaction->type,
            'amount' => $transaction->amount,
        ]);

        return new TransactionResource($transaction);
    }

    public function destroy(Request $request, Account $account, Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        Log::info('Transaction deleted', [
            'transaction_id' => $transaction->id,
            'account_id' => $account->id,
        ]);

        return response()->json(null, 204);
    }

    public function balance(Request $request, Account $account)
    {
        $this->authorize('view', $account);

        $income = $account->transactions()->where('type', 'income')->sum('amount');
        $expense = $account->transactions()->where('type', 'expense')->sum('amount');

        return response()->json([
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
        ]);
    }
}