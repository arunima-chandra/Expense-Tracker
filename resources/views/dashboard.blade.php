<x-layout class="bg-black">
    <div class="mb-8">
        <!-- Dynamic Header based on Mode & Role -->
        @if(Auth::user()->isCompanyAdmin())
            <!-- Company Admin Banner -->
            <div class="bg-gradient-to-r from-blue-950/70 via-gray-900 to-indigo-950/70 border border-blue-800/60 rounded-2xl p-6 mb-8 shadow-xl">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="p-2 rounded-xl bg-blue-600/20 text-blue-400 border border-blue-500/30 text-xl">🏢</span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-100 font-mono">
                                        {{ $company->name ?? 'Company' }}
                                    </h2>
                                    <span class="bg-blue-600 text-white text-xs font-bold font-mono px-2 py-0.5 rounded-md uppercase tracking-wider">Admin</span>
                                </div>
                                <p class="text-xs text-blue-300/80 mt-0.5">Organization Overview & Team Financial Management</p>
                            </div>
                        </div>
                    </div>

                    <!-- Invite Code Widget -->
                    <div class="bg-gray-900/90 border border-blue-900/80 rounded-xl p-3.5 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 shadow-inner">
                        <div>
                            <span class="text-[11px] font-mono uppercase tracking-wider text-gray-400 block">Team Invite Code (Reusable)</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span id="invite-code-text" class="text-xl sm:text-2xl font-black font-mono tracking-widest text-green-400 bg-black/60 px-3 py-1 rounded-lg border border-gray-800 select-all">
                                    {{ $company->invite_code ?? 'N/A' }}
                                </span>
                                <button onclick="copyInviteCode()" class="bg-gray-800 hover:bg-gray-700 text-gray-200 border border-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium transition flex items-center gap-1.5" title="Copy Invite Code">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                    <span id="copy-btn-label">Copy</span>
                                </button>
                            </div>
                        </div>

                        <form method="POST" action="/company/invite-code/regenerate" onsubmit="return confirm('Regenerate invite code? The current code will immediately become invalid for new employees.');" class="sm:border-l sm:border-gray-800 sm:pl-4">
                            @csrf
                            <button type="submit" class="text-xs text-red-400 hover:text-red-300 hover:bg-red-950/40 border border-red-900/50 px-2.5 py-1.5 rounded-lg transition flex items-center gap-1" title="Revoke old code and issue new one">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                <span>Regenerate</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->isCompanyEmployee())
            <!-- Company Employee Banner -->
            <div class="bg-gray-900 border border-indigo-900/50 rounded-2xl p-6 mb-8 shadow-lg">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <span class="p-2 rounded-xl bg-indigo-600/20 text-indigo-400 border border-indigo-500/30 text-xl">💼</span>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-2xl font-bold text-gray-100 font-mono">
                                    {{ $company->name ?? 'Company' }}
                                </h2>
                                <span class="bg-indigo-700 text-indigo-100 text-xs font-bold font-mono px-2 py-0.5 rounded-md uppercase tracking-wider">Employee</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">Your submitted accounts and expense transactions</p>
                        </div>
                    </div>
                    <a href="/accounts" class="bg-gray-800 text-gray-200 hover:text-white hover:bg-gray-700 border border-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Manage My Accounts</span>
                    </a>
                </div>
            </div>
        @else
            <!-- Personal Mode Banner -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-100 font-mono flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Personal Dashboard Overview
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
        @endif

        <!-- Summary KPIs -->
        <div id="kpi-section" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p id="kpi-balance-label" class="text-xs font-mono uppercase tracking-wider text-gray-400">
                    {{ Auth::user()->isCompanyAdmin() ? 'Company Total Balance' : 'Total Portfolio Balance' }}
                </p>
                <p id="kpi-total-balance" class="text-2xl sm:text-3xl font-bold font-mono text-green-400 mt-2">₹0.00</p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p id="kpi-middle-label" class="text-xs font-mono uppercase tracking-wider text-gray-400">
                    {{ Auth::user()->isCompanyAdmin() ? 'Active Team Members' : 'Active Accounts' }}
                </p>
                <p id="kpi-middle-count" class="text-2xl sm:text-3xl font-bold font-mono text-gray-100 mt-2">0</p>
                <div class="absolute right-3 top-3 text-gray-800">
                    @if(Auth::user()->isCompanyAdmin())
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    @else
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    @endif
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                <p class="text-xs font-mono uppercase tracking-wider text-gray-400">Total Recorded Transactions</p>
                <p id="kpi-transaction-count" class="text-2xl sm:text-3xl font-bold font-mono text-gray-100 mt-2">0</p>
                <div class="absolute right-3 top-3 text-gray-800">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Admin: Per-Employee Breakdown Section -->
    @if(Auth::user()->isCompanyAdmin())
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-8 shadow-xl">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-100 font-mono flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Company Employees & Activity Breakdown
                    </h3>
                    <p class="text-xs text-gray-400">List of members enrolled under this company with their submission activity</p>
                </div>
                <div class="text-xs font-mono text-gray-400 bg-gray-950 px-3 py-1.5 rounded-lg border border-gray-800">
                    Total Members: <span id="members-badge-count" class="text-blue-400 font-bold">0</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs font-mono text-gray-400 uppercase bg-gray-950/60 border-b border-gray-800">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg">Employee</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3 text-center">Accounts</th>
                            <th class="px-4 py-3 text-center">Transactions</th>
                            <th class="px-4 py-3 text-right">Total Income</th>
                            <th class="px-4 py-3 text-right">Total Expenses</th>
                            <th class="px-4 py-3 text-right rounded-r-lg">Action</th>
                        </tr>
                    </thead>
                    <tbody id="employees-table-body" class="divide-y divide-gray-800/60">
                        <tr>
                            <td colspan="7" class="py-4 text-center text-gray-500 font-mono text-xs">Loading team activity...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Loading State -->
    <div id="dashboard-loading" class="space-y-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 animate-pulse h-80 flex items-center justify-center">
            <div class="text-center text-gray-500">
                <svg class="animate-spin h-8 w-8 text-green-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="font-mono text-sm">Loading financial metrics...</p>
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
        <h3 class="text-xl font-bold text-gray-100 font-mono mb-2">No Financial Data Found</h3>
        <p class="text-gray-400 max-w-md mx-auto mb-6 text-sm">
            @if(Auth::user()->isCompanyAdmin())
                No accounts or transactions recorded yet across company employees. Share your invite code with staff to start tracking!
            @else
                You haven't added any accounts yet. Create your first account to view charts and running balance history.
            @endif
        </p>
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
                        {{ Auth::user()->isCompanyAdmin() ? 'Company Account Balance Trends' : 'Account Balance History' }}
                    </h3>
                    <p class="text-xs text-gray-400">Cumulative running balance over time for each tracked account</p>
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
                        Portfolio Balance Distribution
                    </h3>
                    <p class="text-xs text-gray-400">Combined balance distribution split across accounts</p>
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

        function copyInviteCode() {
            const codeEl = document.getElementById('invite-code-text');
            if (!codeEl) return;
            const code = codeEl.textContent.trim();
            navigator.clipboard.writeText(code).then(() => {
                const label = document.getElementById('copy-btn-label');
                if (label) {
                    label.textContent = 'Copied!';
                    setTimeout(() => { label.textContent = 'Copy'; }, 2000);
                }
            }).catch(err => {
                console.error('Failed to copy code: ', err);
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
                const mode = data.mode || 'personal';
                const employees = data.employees || [];

                loadingEl.classList.add('hidden');

                // Render employee table if in company admin mode
                if (mode === 'company_admin') {
                    renderEmployeesTable(employees);
                    document.getElementById('kpi-middle-count').textContent = employees.length;
                    const badgeCount = document.getElementById('members-badge-count');
                    if (badgeCount) badgeCount.textContent = employees.length;
                } else {
                    document.getElementById('kpi-middle-count').textContent = accounts.length;
                }

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

        function renderEmployeesTable(employees) {
            const tbody = document.getElementById('employees-table-body');
            if (!tbody) return;

            if (employees.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="py-6 text-center text-gray-500 font-mono text-xs italic">
                            No employees have joined yet. Share your invite code to invite team members!
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = employees.map(emp => {
                const isAdmin = emp.role === 'admin';
                const roleBadge = isAdmin
                    ? `<span class="bg-blue-900/60 text-blue-400 border border-blue-800 text-[10px] px-2 py-0.5 rounded font-bold font-mono uppercase">Admin (Owner)</span>`
                    : `<span class="bg-indigo-900/60 text-indigo-300 border border-indigo-800 text-[10px] px-2 py-0.5 rounded font-bold font-mono uppercase">Employee</span>`;

                const actionHtml = emp.is_self
                    ? `<span class="text-xs text-gray-500 italic">Current User</span>`
                    : `
                        <form method="POST" action="/company/members/${emp.id}" onsubmit="return confirm('Remove ${emp.name} from this company? Their access will be reverted to personal mode.');">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-400 hover:text-red-300 hover:underline text-xs font-mono transition">Remove</button>
                        </form>
                    `;

                return `
                    <tr class="hover:bg-gray-800/40 transition">
                        <td class="px-4 py-3 font-medium text-gray-200">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gray-800 flex items-center justify-center text-xs font-bold text-gray-300">
                                    ${emp.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-200 leading-tight">${emp.name}</p>
                                    <p class="text-[11px] text-gray-500 font-mono">${emp.email}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">${roleBadge}</td>
                        <td class="px-4 py-3 text-center font-mono text-gray-300">${emp.accounts_count}</td>
                        <td class="px-4 py-3 text-center font-mono text-gray-300">${emp.transactions_count}</td>
                        <td class="px-4 py-3 text-right font-mono text-green-400">${formatCurrency(emp.total_income)}</td>
                        <td class="px-4 py-3 text-right font-mono text-red-400">${formatCurrency(emp.total_expense)}</td>
                        <td class="px-4 py-3 text-right">${actionHtml}</td>
                    </tr>
                `;
            }).join('');
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
