<?php

use App\Models\Account;
use App\Models\ImportantDate;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Show login/register pages
Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
});

// Handle register
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Auth::login($user);

    return redirect('/dashboard');
});

// Handle login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        return redirect('/dashboard');
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
});

// Handle logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    return redirect('/login');
});

// Protected routes (must be logged in)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('/dashboard-data', function () {
        $accounts = Auth::user()->accounts()
            ->with(['transactions' => function ($query) {
                $query->orderBy('date', 'asc')->orderBy('id', 'asc');
            }])
            ->get()
            ->map(function ($account) {
                $running = 0;
                $history = [];

                foreach ($account->transactions as $tx) {
                    if ($tx->type === 'income') {
                        $running += (float) $tx->amount;
                    } else {
                        $running -= (float) $tx->amount;
                    }
                    $history[] = [
                        'id' => $tx->id,
                        'date' => $tx->date,
                        'type' => $tx->type,
                        'category' => $tx->category,
                        'amount' => (float) $tx->amount,
                        'balance' => round($running, 2),
                    ];
                }

                return [
                    'id' => $account->id,
                    'name' => $account->name,
                    'type' => $account->type,
                    'current_balance' => round($running, 2),
                    'balance_history' => $history,
                    'transactions' => $account->transactions,
                ];
            });

        $totalBalance = $accounts->sum('current_balance');

        return response()->json([
            'accounts' => $accounts,
            'total_balance' => round($totalBalance, 2),
        ]);
    });

    Route::get('/accounts', function () {
        $accounts = Auth::user()->accounts()->with(['transactions' => function ($query) {
            $query->orderBy('date', 'desc')->orderBy('id', 'desc');
        }])->get()->map(function ($account) {
            $income = $account->transactions->where('type', 'income')->sum('amount');
            $expense = $account->transactions->where('type', 'expense')->sum('amount');
            $account->balance = $income - $expense;
            return $account;
        });

        return view('accounts', compact('accounts'));
    });

    Route::post('/accounts', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        Account::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'type' => $validated['type'],
        ]);

        return redirect('/accounts');
    });

    Route::put('/accounts/{account}', function (Request $request, Account $account) {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
        ]);

        $account->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
        ]);

        return redirect('/accounts');
    });

    Route::delete('/accounts/{account}', function (Account $account) {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $account->transactions()->delete();
        $account->delete();

        return redirect('/accounts');
    });

    Route::post('/accounts/{account}/transactions', function (Request $request, Account $account) {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'category' => 'nullable|string',
            'date' => 'required|date',
        ]);

        $account->transactions()->create($validated);

        return redirect('/accounts');
    });

    Route::delete('/accounts/{account}/transactions/{transaction}', function (Account $account, Transaction $transaction) {
        if ($account->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();
        return redirect('/accounts');
    });

    // Reports Web Routes
    Route::get('/reports', function () {
        return redirect('/reports/' . date('Y') . '/' . date('n'));
    });

    Route::get('/reports/{year}', function (int $year) {
        $userId = Auth::id();
        $transactions = Transaction::forUser($userId)->inYear($year)->get();

        $monthsData = [];
        $totalIncome = 0;
        $totalExpense = 0;

        for ($m = 1; $m <= 12; $m++) {
            $monthTx = $transactions->filter(function ($t) use ($m) {
                return (int) Carbon::parse($t->date)->format('n') === $m;
            });

            $income = (float) $monthTx->where('type', 'income')->sum('amount');
            $expense = (float) $monthTx->where('type', 'expense')->sum('amount');
            $balance = round($income - $expense, 2);

            $totalIncome += $income;
            $totalExpense += $expense;

            $monthsData[] = [
                'month' => $m,
                'month_name' => Carbon::create()->month($m)->translatedFormat('F'),
                'income' => $income,
                'expense' => $expense,
                'balance' => $balance,
                'transaction_count' => $monthTx->count(),
            ];
        }

        $netBalance = round($totalIncome - $totalExpense, 2);

        return view('reports.yearly', compact('year', 'monthsData', 'totalIncome', 'totalExpense', 'netBalance'));
    });

    Route::get('/reports/{year}/{month}', function (int $year, int $month) {
        $userId = Auth::id();
        $transactions = Transaction::forUser($userId)
            ->inMonth($year, $month)
            ->with('account')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalIncome = (float) $transactions->where('type', 'income')->sum('amount');
        $totalExpense = (float) $transactions->where('type', 'expense')->sum('amount');
        $netBalance = round($totalIncome - $totalExpense, 2);

        $monthName = Carbon::createFromDate($year, $month, 1)->translatedFormat('F');

        $categoryBreakdown = $transactions->groupBy('category')->map(function ($items, $category) {
            return [
                'category' => $category ?: 'General',
                'income' => (float) $items->where('type', 'income')->sum('amount'),
                'expense' => (float) $items->where('type', 'expense')->sum('amount'),
                'total' => (float) $items->sum('amount'),
                'count' => $items->count(),
            ];
        })->values();

        return view('reports.monthly', compact('year', 'month', 'monthName', 'transactions', 'totalIncome', 'totalExpense', 'netBalance', 'categoryBreakdown'));
    });

    // Important Dates / Reminders Web Routes
    Route::get('/important-dates', function () {
        $dates = Auth::user()->importantDates()
            ->orderBy('is_recurring', 'desc')
            ->orderBy('due_day', 'asc')
            ->orderBy('due_date', 'asc')
            ->get();

        return view('important-dates', compact('dates'));
    });

    Route::post('/important-dates', function (Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:bill,rent,emi,salary,other',
            'amount' => 'nullable|numeric|min:0.01',
            'due_day' => 'nullable|integer|min:1|max:31',
            'due_date' => 'nullable|date',
            'reminder_method' => 'required|in:notification,alarm,both',
            'is_recurring' => 'nullable|boolean',
        ]);

        $isRecurring = $request->has('is_recurring') && $request->boolean('is_recurring');
        $validated['is_recurring'] = $isRecurring;

        Auth::user()->importantDates()->create($validated);

        return redirect('/important-dates')->with('success', 'Reminder added successfully!');
    });

    Route::delete('/important-dates/{importantDate}', function (ImportantDate $importantDate) {
        if ($importantDate->user_id !== Auth::id()) {
            abort(403);
        }

        $importantDate->delete();
        return redirect('/important-dates')->with('success', 'Reminder deleted.');
    });

    Route::get('/important-dates-alarms/today', function (Request $request) {
        $alarms = Auth::user()->importantDates()->forAlarm()->dueToday()->get();
        return response()->json($alarms);
    });
});