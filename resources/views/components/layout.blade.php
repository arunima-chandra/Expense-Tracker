<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@3.5.0/build/global/luxon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.3.1/dist/chartjs-adapter-luxon.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-financial@0.1.1/dist/chartjs-chart-financial.min.js"></script>
    <style>
        body {
            font-family: Calibri, sans-serif;
        }
    </style>
</head>
<body {{ $attributes->merge(['class' => 'bg-gray-950 text-gray-100 min-h-screen']) }}>
    <nav class="bg-gray-900 border-b border-gray-800 text-white p-4 sticky top-0 z-50 backdrop-blur bg-gray-900/90">
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="/dashboard" class="text-xl font-bold font-mono tracking-wide text-gray-100 flex items-center gap-2 hover:text-green-400 transition">
                    <span class="text-green-400 font-extrabold text-2xl">₹</span>
                    <span>Expense Tracker</span>
                </a>
                @auth
                    <div class="flex items-center gap-2 text-sm font-medium">
                        <a href="/dashboard" class="px-3 py-1.5 rounded transition flex items-center gap-1.5 {{ request()->is('dashboard*') ? 'bg-gray-800 text-green-400 font-semibold border border-gray-700' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                        <a href="/accounts" class="px-3 py-1.5 rounded transition flex items-center gap-1.5 {{ request()->is('accounts*') ? 'bg-gray-800 text-green-400 font-semibold border border-gray-700' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span>Accounts</span>
                        </a>
                        <a href="/reports" class="px-3 py-1.5 rounded transition flex items-center gap-1.5 {{ request()->is('reports*') ? 'bg-gray-800 text-green-400 font-semibold border border-gray-700' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Reports</span>
                        </a>
                        <a href="/important-dates" class="px-3 py-1.5 rounded transition flex items-center gap-1.5 {{ request()->is('important-dates*') ? 'bg-gray-800 text-green-400 font-semibold border border-gray-700' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Reminders</span>
                        </a>
                    </div>
                @endauth
            </div>
            @auth
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-400 font-mono hidden sm:inline">{{ Auth::user()->name }}</span>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="bg-gray-800 text-gray-300 hover:text-white hover:bg-gray-700 border border-gray-700 px-3 py-1.5 rounded text-xs sm:text-sm transition">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    @auth
        <!-- Alarm / Due Date Notification Modal Banner -->
        <div id="alarm-banner-container" class="max-w-5xl mx-auto px-4 mt-3 hidden"></div>
        <script>
            document.addEventListener('DOMContentLoaded', async function () {
                try {
                    const res = await fetch('/important-dates-alarms/today');
                    if (res.ok) {
                        const alarms = await res.json();
                        if (alarms && alarms.length > 0) {
                            const banner = document.getElementById('alarm-banner-container');
                            if (banner) {
                                banner.classList.remove('hidden');
                                banner.innerHTML = `
                                    <div class="bg-amber-950/80 border border-amber-600/80 text-amber-200 p-4 rounded-xl shadow-lg flex items-start justify-between gap-4 animate-bounce-short">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 bg-amber-600/20 text-amber-400 rounded-lg">
                                                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold font-mono text-base text-amber-100 flex items-center gap-2">
                                                    ⚠️ Due Date Alert (Today!)
                                                </h4>
                                                <div class="mt-1 space-y-1 text-sm">
                                                    ${alarms.map(a => `
                                                        <p>• <span class="font-semibold">${a.title}</span> (${a.type.toUpperCase()}) ${a.amount ? '- ₹' + Number(a.amount).toLocaleString('en-IN') : ''}</p>
                                                    `).join('')}
                                                </div>
                                            </div>
                                        </div>
                                        <button onclick="document.getElementById('alarm-banner-container').classList.add('hidden')" class="text-amber-400 hover:text-amber-200 p-1 rounded transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                `;
                            }
                        }
                    }
                } catch (err) {
                    console.log('Alarm poll check:', err);
                }
            });
        </script>
    @endauth

    <main class="max-w-5xl mx-auto mt-6 p-4">
        {{ $slot }}
    </main>
</body>
</html>