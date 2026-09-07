<x-layout class="bg-black">
    <div class="mb-8">
        <!-- Header & Navigation -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <div class="flex items-center gap-3">
                    <a href="/reports/{{ $year - 1 }}" class="p-2 bg-gray-900 border border-gray-800 rounded-lg text-gray-400 hover:text-white hover:border-gray-700 transition" title="Previous Year">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h2 class="text-2xl font-bold text-gray-100 font-mono">
                        {{ $year }} Annual Report
                    </h2>
                    <a href="/reports/{{ $year + 1 }}" class="p-2 bg-gray-900 border border-gray-800 rounded-lg text-gray-400 hover:text-white hover:border-gray-700 transition" title="Next Year">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <p class="text-sm text-gray-400 mt-1">12-month financial trajectory and expense trends</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="/reports/{{ $year }}/{{ date('n') }}" class="bg-gray-800 text-gray-200 hover:text-white hover:bg-gray-700 border border-gray-700 px-3 py-1.5 rounded-lg text-sm transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Current Month</span>
                </a>
            </div>
        </div>

        <!-- Annual Summary KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Total Annual Income</p>
                <p class="text-2xl sm:text-3xl font-bold font-mono text-green-400 mt-2">
                    ₹{{ number_format($totalIncome, 2) }}
                </p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Total Annual Expenses</p>
                <p class="text-2xl sm:text-3xl font-bold font-mono text-red-400 mt-2">
                    ₹{{ number_format($totalExpense, 2) }}
                </p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Net Annual Balance</p>
                <p class="text-2xl sm:text-3xl font-bold font-mono {{ $netBalance >= 0 ? 'text-green-400' : 'text-red-400' }} mt-2">
                    {{ $netBalance >= 0 ? '+' : '' }}₹{{ number_format($netBalance, 2) }}
                </p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        <!-- 12-Month Expense & Income Trend Chart -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-100 font-mono flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        <span>{{ $year }} Monthly Expense & Income Trend</span>
                    </h3>
                    <p class="text-xs text-gray-400">Comparison of monthly inflows vs outflows</p>
                </div>
            </div>

            <div class="h-80 w-full relative">
                <canvas id="yearlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Month-by-Month Summary Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-bold text-gray-100 font-mono mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M3 18h18M3 6h18"></path></svg>
                <span>Month-by-Month Breakdown</span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs font-mono text-gray-400 border-b border-gray-800">
                            <th class="py-3 px-3">Month</th>
                            <th class="py-3 px-3 text-right">Income</th>
                            <th class="py-3 px-3 text-right">Expense</th>
                            <th class="py-3 px-3 text-right">Net Balance</th>
                            <th class="py-3 px-3 text-center">Transactions</th>
                            <th class="py-3 px-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthsData as $m)
                            <tr class="border-b border-gray-800/60 hover:bg-gray-800/40 transition">
                                <td class="py-3 px-3 font-medium text-gray-200">
                                    <span class="font-mono text-xs text-gray-500 mr-2">{{ sprintf('%02d', $m['month']) }}</span>
                                    {{ $m['month_name'] }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-green-400">
                                    ₹{{ number_format($m['income'], 2) }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono text-red-400">
                                    ₹{{ number_format($m['expense'], 2) }}
                                </td>
                                <td class="py-3 px-3 text-right font-mono font-bold {{ $m['balance'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $m['balance'] >= 0 ? '+' : '' }}₹{{ number_format($m['balance'], 2) }}
                                </td>
                                <td class="py-3 px-3 text-center font-mono text-xs text-gray-400">
                                    {{ $m['transaction_count'] }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="/reports/{{ $year }}/{{ $m['month'] }}" class="text-xs text-blue-400 hover:text-blue-300 hover:underline">
                                        View Details →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-700 font-bold bg-gray-800/40">
                            <td class="py-3.5 px-3 font-mono text-gray-100">Annual Total</td>
                            <td class="py-3.5 px-3 text-right font-mono text-green-400">₹{{ number_format($totalIncome, 2) }}</td>
                            <td class="py-3.5 px-3 text-right font-mono text-red-400">₹{{ number_format($totalExpense, 2) }}</td>
                            <td class="py-3.5 px-3 text-right font-mono {{ $netBalance >= 0 ? 'text-green-400' : 'text-red-400' }}">
                                {{ $netBalance >= 0 ? '+' : '' }}₹{{ number_format($netBalance, 2) }}
                            </td>
                            <td class="py-3.5 px-3 text-center font-mono text-xs text-gray-300">
                                {{ array_sum(array_column($monthsData, 'transaction_count')) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const monthsData = {!! json_encode($monthsData) !!};
            const ctx = document.getElementById('yearlyTrendChart');
            if (!ctx) return;

            const labels = monthsData.map(m => m.month_name.substring(0, 3));
            const expenses = monthsData.map(m => m.expense);
            const incomes = monthsData.map(m => m.income);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Expenses',
                            data: expenses,
                            backgroundColor: 'rgba(239, 68, 68, 0.75)',
                            borderColor: '#ef4444',
                            borderWidth: 1.5,
                            borderRadius: 6,
                        },
                        {
                            label: 'Income',
                            data: incomes,
                            backgroundColor: 'rgba(34, 197, 94, 0.75)',
                            borderColor: '#22c55e',
                            borderWidth: 1.5,
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#d1d5db',
                                font: { size: 12 },
                                usePointStyle: true,
                                padding: 16
                            }
                        },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleColor: '#f3f4f6',
                            bodyColor: '#e5e7eb',
                            borderColor: '#374151',
                            borderWidth: 1,
                            padding: 10,
                            callbacks: {
                                label: function (context) {
                                    const val = context.parsed.y;
                                    return ` ${context.dataset.label}: ₹${Number(val).toLocaleString('en-IN', { minimumFractionDigits: 2 })}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(75, 85, 99, 0.15)' },
                            ticks: { color: '#9ca3af', font: { size: 11, family: 'monospace' } }
                        },
                        y: {
                            grid: { color: 'rgba(75, 85, 99, 0.15)' },
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 11, family: 'monospace' },
                                callback: function (value) {
                                    return '₹' + Number(value).toLocaleString('en-IN');
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-layout>
