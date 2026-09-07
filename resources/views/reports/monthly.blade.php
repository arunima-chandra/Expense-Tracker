<x-layout class="bg-black">
    @php
        $prevYear = $month == 1 ? $year - 1 : $year;
        $prevMonth = $month == 1 ? 12 : $month - 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        $nextMonth = $month == 12 ? 1 : $month + 1;
    @endphp

    <div class="mb-8">
        <!-- Header & Navigation -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <a href="/reports/{{ $prevYear }}/{{ $prevMonth }}" class="p-2 bg-gray-900 border border-gray-800 rounded-lg text-gray-400 hover:text-white hover:border-gray-700 transition" title="Previous Month">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h2 class="text-2xl font-bold text-gray-100 font-mono flex items-center gap-2">
                        {{ $monthName }} {{ $year }}
                    </h2>
                    <a href="/reports/{{ $nextYear }}/{{ $nextMonth }}" class="p-2 bg-gray-900 border border-gray-800 rounded-lg text-gray-400 hover:text-white hover:border-gray-700 transition" title="Next Month">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <p class="text-sm text-gray-400 mt-1">Monthly expense analysis and transaction breakdown</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="/reports/{{ $year }}" class="bg-gray-800 text-gray-200 hover:text-white hover:bg-gray-700 border border-gray-700 px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>{{ $year }} Yearly Summary</span>
                </a>
            </div>
        </div>

        <!-- Monthly KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Total Income</p>
                <p class="text-2xl sm:text-3xl font-bold font-mono text-green-400 mt-2">
                    ₹{{ number_format($totalIncome, 2) }}
                </p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Total Expenses</p>
                <p class="text-2xl sm:text-3xl font-bold font-mono text-red-400 mt-2">
                    ₹{{ number_format($totalExpense, 2) }}
                </p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Net Savings / Balance</p>
                <p class="text-2xl sm:text-3xl font-bold font-mono {{ $netBalance >= 0 ? 'text-green-400' : 'text-red-400' }} mt-2">
                    {{ $netBalance >= 0 ? '+' : '' }}₹{{ number_format($netBalance, 2) }}
                </p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- Category Breakdown & Spending Distribution -->
        @if($categoryBreakdown->isNotEmpty())
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-8">
                <h3 class="text-lg font-bold text-gray-100 font-mono mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                    <span>Spending by Category</span>
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($categoryBreakdown as $cat)
                        <div class="bg-gray-800/60 border border-gray-800 rounded-lg p-3.5 flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-200 text-sm">{{ $cat['category'] }}</p>
                                <p class="text-xs text-gray-400">{{ $cat['count'] }} transaction(s)</p>
                            </div>
                            <div class="text-right">
                                @if($cat['expense'] > 0)
                                    <p class="font-mono text-sm font-semibold text-red-400">-₹{{ number_format($cat['expense'], 2) }}</p>
                                @endif
                                @if($cat['income'] > 0)
                                    <p class="font-mono text-sm font-semibold text-green-400">+₹{{ number_format($cat['income'], 2) }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Transactions Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-100 font-mono flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    <span>Transactions in {{ $monthName }} ({{ $transactions->count() }})</span>
                </h3>
            </div>

            @if($transactions->isEmpty())
                <div class="py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="font-medium">No transactions found for this month.</p>
                    <a href="/accounts" class="text-xs text-green-400 hover:underline mt-1 inline-block">Add transactions in Accounts page</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-mono text-gray-400 border-b border-gray-800">
                                <th class="py-3 px-2">Date</th>
                                <th class="py-3 px-2">Account</th>
                                <th class="py-3 px-2">Category</th>
                                <th class="py-3 px-2">Description</th>
                                <th class="py-3 px-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $tx)
                                <tr class="border-b border-gray-800/60 hover:bg-gray-800/40 transition">
                                    <td class="py-3 px-2 font-mono text-gray-400 whitespace-nowrap">{{ $tx->date }}</td>
                                    <td class="py-3 px-2">
                                        <span class="px-2 py-0.5 text-xs bg-gray-800 border border-gray-700 rounded text-gray-300">{{ $tx->account->name ?? 'Account' }}</span>
                                    </td>
                                    <td class="py-3 px-2 text-gray-200">{{ $tx->category ?: 'General' }}</td>
                                    <td class="py-3 px-2 text-gray-400 text-xs truncate max-w-xs">{{ $tx->description ?: '—' }}</td>
                                    <td class="py-3 px-2 text-right font-mono font-bold whitespace-nowrap {{ $tx->type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }}₹{{ number_format($tx->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layout>
