<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\AccountCreated;

class AccountController extends Controller
{
    public function index(Request $request)
{
    return AccountResource::collection($request->user()->accounts);
}

    public function store(StoreAccountRequest $request)
{
    $account = $request->user()->accounts()->create($request->validated());

    event(new AccountCreated($account));

    return response()->json($account, 201);
}

    public function show(Request $request, Account $account)
{
    $this->authorize('view', $account);

    return new AccountResource($account);
}


    public function update(UpdateAccountRequest $request, Account $account)
{
    $account->update($request->validated());

    Log::info('Account updated', ['account_id' => $account->id]);

    return $account;
}

    public function destroy(Request $request, Account $account)
{
    $this->authorize('delete', $account);

    $account->delete();

    Log::info('Account deleted', ['account_id' => $account->id]);

    return response()->json(null, 204);
}
}