<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->accounts;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        $account = $request->user()->accounts()->create($validated);

        Log::info('Account created', [
            'account_id' => $account->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($account, 201);
    }

    public function show(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) {
            Log::warning('Unauthorized account access attempt', [
                'user_id' => $request->user()->id,
                'account_id' => $account->id,
            ]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $account;
    }

    public function update(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) {
            Log::warning('Unauthorized account update attempt', [
                'user_id' => $request->user()->id,
                'account_id' => $account->id,
            ]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string',
            'type' => 'sometimes|string',
        ]);

        $account->update($validated);

        Log::info('Account updated', ['account_id' => $account->id]);

        return $account;
    }

    public function destroy(Request $request, Account $account)
    {
        if ($account->user_id !== $request->user()->id) {
            Log::warning('Unauthorized account delete attempt', [
                'user_id' => $request->user()->id,
                'account_id' => $account->id,
            ]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $account->delete();

        Log::info('Account deleted', ['account_id' => $account->id]);

        return response()->json(null, 204);
    }
}