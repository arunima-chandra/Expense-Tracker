<x-layout class="bg-black">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-100 font-mono flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Dashboard Overview
                </h2>
                <p class="text-sm text-gray-400 mt-1">Real-time running balances and portfolio distribution</p>
            </div>
            <a href="/accounts" class="bg-gray-800 text-gray-200 hover:text-white hover:bg-gray-700 border border-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Manage Accounts</span>
            </a>
        </div>

        <!-- Summary KPIs -->
        <div id="kpi-section" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Total Portfolio Balance</p>
                <p id="kpi-total-balance" class="text-2xl sm:text-3xl font-bold font-mono text-green-400 mt-2">₹0.00</p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Active Accounts</p>
                <p id="kpi-account-count" class="text-2xl sm:text-3xl font-bold font-mono text-gray-100 mt-2">0</p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Total Transactions</p>
                <p id="kpi-transaction-count" class="text-2xl sm:text-3xl font-bold font-mono text-gray-100 mt-2">0</p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="dashboard-loading" class="space-y-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 animate-pulse h-80 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="animate-spin h-8 w-8 text-green-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="font-mono text-sm">Loading financial data...</p>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="dashboard-empty" class="hidden bg-gray-900 border border-gray-800 rounded-xl p-12 text-center">
        <div class="w-16 h-16 bg-gray-800 text-green-400 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-100 font-mono mb-2">No Accounts Found</h3>
        <p class="text-gray-400 max-w-md mx-auto mb-6 text-sm">You haven't added any accounts yet. Create your first account to view charts and running balance history.</p>
        <a href="/accounts" class="inline-flex items-center gap-2 bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-500 font-medium transition">
            <span>Add First Account</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </a>
    </div>

    <!-- Content Section -->
    <div id="dashboard-content" class="hidden space-y-8">
        <!-- Section: Line charts per account -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-100 font-mono flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        Account Balance History
                    </h3>
                    <p class="text-xs text-gray-400">Cumulative running balance over time for each account</p>
                </div>
            </div>

            <div id="account-charts-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Dynamically populated per account -->
            </div>
        </div>

        <!-- Section: Pie chart total balance distribution -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-100 font-mono flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        Total Balance Distribution
                    </h3>
                    <p class="text-xs text-gray-400">Combined portfolio balance split across all accounts</p>
                </div>
                <div id="pie-total-badge" class="font-mono text-sm px-3 py-1 bg-gray-800 border border-gray-700 rounded-lg text-gray-300">
                    Total: <span id="pie-total-amount" class="text-green-400 font-bold">₹0.00</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <div class="md:col-span-7 h-72 relative flex items-center justify-center">
                    <canvas id="balanceDistributionChart"></canvas>
                </div>
                <div class="md:col-span-5">
                    <div id="distribution-legend" class="space-y-2.5 max-h-72 overflow-y-auto pr-1">
                        <!-- Dynamically populated legend items -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetchDashboardData();
        });

        // Curated vibrant color palette for dark mode charts
        const chartColors = [
            { border: '#22c55e', bg: 'rgba(34, 197, 94, 0.15)', solid: '#22c55e' }, // Emerald Green
            { border: '#38bdf8', bg: 'rgba(56, 189, 248, 0.15)', solid: '#38bdf8' }, // Sky Blue
            { border: '#f59e0b', bg: 'rgba(245, 158, 11, 0.15)', solid: '#f59e0b' }, // Amber
            { border: '#ec4899', bg: 'rgba(236, 72, 153, 0.15)', solid: '#ec4899' }, // Pink
            { border: '#a855f7', bg: 'rgba(168, 85, 247, 0.15)', solid: '#a855f7' }, // Purple
            { border: '#14b8a6', bg: 'rgba(20, 184, 166, 0.15)', solid: '#14b8a6' }, // Teal
            { border: '#f97316', bg: 'rgba(249, 115, 22, 0.15)', solid: '#f97316' }, // Orange
            { border: '#6366f1', bg: 'rgba(99, 102, 241, 0.15)', solid: '#6366f1' }, // Indigo
        ];

        function formatCurrency(amount) {
            return '₹' + Number(amount).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        async function fetchDashboardData() {
            const loadingEl = document.getElementById('dashboard-loading');
            const emptyEl = document.getElementById('dashboard-empty');
            const contentEl = document.getElementById('dashboard-content');

            try {
                const response = await fetch('/dashboard-data', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                const accounts = data.accounts || [];
                const totalBalance = data.total_balance || 0;

                loadingEl.classList.add('hidden');

                if (accounts.length === 0) {
                    emptyEl.classList.remove('hidden');
                    return;
                }

                contentEl.classList.remove('hidden');

                // Update KPIs
                let totalTransactions = 0;
                accounts.forEach(a => {
                    totalTransactions += (a.transactions ? a.transactions.length : 0);
                });

                const totalBalEl = document.getElementById('kpi-total-balance');
                totalBalEl.textContent = formatCurrency(totalBalance);
                totalBalEl.className = `text-2xl sm:text-3xl font-bold font-mono mt-2 ${totalBalance >= 0 ? 'text-green-400' : 'text-red-400'}`;

                document.getElementById('kpi-account-count').textContent = accounts.length;
                document.getElementById('kpi-transaction-count').textContent = totalTransactions;
                document.getElementById('pie-total-amount').textContent = formatCurrency(totalBalance);
                document.getElementById('pie-total-amount').className = `font-bold ${totalBalance >= 0 ? 'text-green-400' : 'text-red-400'}`;

                // Render Line Charts
                renderAccountLineCharts(accounts);

                // Render Pie Chart
                renderBalanceDistributionChart(accounts, totalBalance);

            } catch (error) {
                console.error('Error loading dashboard data:', error);
                loadingEl.innerHTML = `
                    <div class="bg-gray-900 border border-red-800/50 rounded-xl p-8 text-center text-red-400">
                        <p class="font-semibold mb-2">Failed to load dashboard data</p>
                        <p class="text-xs text-gray-500 mb-4">Please check your connection and try again.</p>
                        <button onclick="location.reload()" class="bg-gray-800 hover:bg-gray-700 text-gray-200 px-4 py-2 rounded text-sm transition">Retry</button>
                    </div>
                `;
            }
        }

        function renderAccountLineCharts(accounts) {
            const container = document.getElementById('account-charts-container');
            container.innerHTML = '';

            accounts.forEach((account, index) => {
                const colorTheme = chartColors[index % chartColors.length];
                const card = document.createElement('div');
                card.className = 'bg-gray-900 border border-gray-800 rounded-xl p-5 flex flex-col justify-between';

                const balanceFormatted = formatCurrency(account.current_balance);
                const balanceColorClass = account.current_balance >= 0 ? 'text-green-400' : 'text-red-400';
                const canvasId = `accountChart-${account.id}`;

                card.innerHTML = `
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full" style="background-color: ${colorTheme.solid};"></span>
                                <h4 class="text-lg font-bold text-gray-100">${account.name}</h4>
                            </div>
                            <span class="text-xs font-mono uppercase tracking-wider text-gray-400">${account.type}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-400 font-mono block">Current Balance</span>
                            <span class="text-xl font-bold font-mono ${balanceColorClass}">${balanceFormatted}</span>
                        </div>
                    </div>
                    <div class="h-60 w-full relative">
                        <canvas id="${canvasId}"></canvas>
                    </div>
                `;

                container.appendChild(card);

                // Prepare chart data
                const history = account.balance_history || [];
                const canvas = document.getElementById(canvasId);

                if (history.length === 0) {
                    // Empty state for this account
                    canvas.parentElement.innerHTML = `
                        <div class="h-full flex flex-col items-center justify-center text-gray-500 text-center p-4">
                            <svg class="w-8 h-8 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <p class="text-sm font-medium">No transactions recorded yet</p>
                            <a href="/accounts" class="text-xs text-green-400 hover:underline mt-1">Add transaction on Accounts page</a>
                        </div>
                    `;
                    return;
                }

                const labels = history.map(item => item.date);
                const dataPoints = history.map(item => item.balance);

                new Chart(canvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `${account.name} Balance`,
                            data: dataPoints,
                            borderColor: colorTheme.border,
                            backgroundColor: colorTheme.bg,
                            borderWidth: 2.5,
                            tension: 0.3,
                            fill: true,
                            pointBackgroundColor: colorTheme.border,
                            pointBorderColor: '#111827',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                        }]
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
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#111827',
                                titleColor: '#f3f4f6',
                                bodyColor: '#e5e7eb',
                                borderColor: '#374151',
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                                callbacks: {
                                    label: function (ctx) {
                                        const raw = ctx.parsed.y;
                                        return `Running Balance: ${formatCurrency(raw)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    color: 'rgba(75, 85, 99, 0.15)',
                                },
                                ticks: {
                                    color: '#9ca3af',
                                    font: { size: 10, family: 'monospace' },
                                    maxRotation: 45,
                                    autoSkip: true,
                                    maxTicksLimit: 7
                                }
                            },
                            y: {
                                grid: {
                                    color: 'rgba(75, 85, 99, 0.15)',
                                },
                                ticks: {
                                    color: '#9ca3af',
                                    font: { size: 10, family: 'monospace' },
                                    callback: function (value) {
                                        return '₹' + Number(value).toLocaleString('en-IN');
                                    }
                                }
                            }
                        }
                    }
                });
            });
        }

        function renderBalanceDistributionChart(accounts, totalBalance) {
            const canvas = document.getElementById('balanceDistributionChart');
            const legendContainer = document.getElementById('distribution-legend');
            legendContainer.innerHTML = '';

            const validAccounts = accounts.map((acc, index) => {
                const color = chartColors[index % chartColors.length].solid;
                return {
                    name: acc.name,
                    type: acc.type,
                    balance: acc.current_balance,
                    color: color
                };
            });

            // Calculate positive balances for pie chart representation
            const pieData = validAccounts.map(a => Math.max(0, a.balance));
            const positiveSum = pieData.reduce((sum, v) => sum + v, 0);

            // Populate custom legend
            validAccounts.forEach(acc => {
                const percentage = positiveSum > 0 && acc.balance > 0 
                    ? ((acc.balance / positiveSum) * 100).toFixed(1) 
                    : 0;

                const legendItem = document.createElement('div');
                legendItem.className = 'flex items-center justify-between p-2.5 rounded-lg bg-gray-800/60 border border-gray-800 hover:border-gray-700 transition text-sm';
                legendItem.innerHTML = `
                    <div class="flex items-center gap-2.5 min-w-0">
                        <span class="w-3 h-3 rounded-full flex-shrink-0" style="background-color: ${acc.color};"></span>
                        <div class="truncate">
                            <p class="font-medium text-gray-200 truncate">${acc.name}</p>
                            <p class="text-xs text-gray-500 font-mono uppercase">${acc.type}</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-2">
                        <p class="font-mono font-semibold ${acc.balance >= 0 ? 'text-gray-200' : 'text-red-400'}">${formatCurrency(acc.balance)}</p>
                        <p class="text-xs text-gray-400 font-mono">${percentage}%</p>
                    </div>
                `;
                legendContainer.appendChild(legendItem);
            });

            if (positiveSum === 0) {
                canvas.parentElement.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-gray-500 text-center p-4">
                        <svg class="w-10 h-10 mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                        <p class="text-sm font-medium">All accounts currently have ₹0.00 or negative balance</p>
                        <p class="text-xs text-gray-500 mt-1">Add income transactions on the Accounts page to see distribution</p>
                    </div>
                `;
                return;
            }

            new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels: validAccounts.map(a => a.name),
                    datasets: [{
                        data: pieData,
                        backgroundColor: validAccounts.map(a => a.color),
                        borderColor: '#111827',
                        borderWidth: 3,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '62%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#111827',
                            titleColor: '#f3f4f6',
                            bodyColor: '#e5e7eb',
                            borderColor: '#374151',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: function (ctx) {
                                    const val = ctx.parsed;
                                    const pct = positiveSum > 0 ? ((val / positiveSum) * 100).toFixed(1) : 0;
                                    return ` ${ctx.label}: ${formatCurrency(val)} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-layout>
