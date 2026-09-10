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
        <div class="max-w-6xl mx-auto flex flex-wrap justify-between items-center gap-4">
            <div class="flex items-center gap-6">
                <a href="/dashboard" class="text-xl font-bold font-mono tracking-wide text-gray-100 flex items-center gap-2 hover:text-green-400 transition">
                    <span class="text-green-400 font-extrabold text-2xl">₹</span>
                    <span>Expense Tracker</span>
                </a>
                @auth
                    <div class="flex items-center gap-2 text-sm font-medium flex-wrap">
                        <a href="/" class="px-3 py-1.5 rounded transition flex items-center gap-1.5 {{ (request()->is('/') || request()->path() === '/') ? 'bg-gray-800 text-green-400 font-semibold border border-gray-700' : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/60' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            <span>Home</span>
                        </a>
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
                    <!-- Mode / Role Badge -->
                    @if(Auth::user()->isCompanyAdmin())
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-mono font-semibold bg-blue-950 text-blue-300 border border-blue-800" title="Company Admin">
                            <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                            <span>🏢 {{ Auth::user()->company->name ?? 'Company' }}</span>
                            <span class="bg-blue-800 text-blue-100 text-[10px] px-1.5 py-0.5 rounded font-bold">ADMIN</span>
                        </div>
                    @elseif(Auth::user()->isCompanyEmployee())
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-mono font-semibold bg-indigo-950 text-indigo-300 border border-indigo-800" title="Company Employee">
                            <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                            <span>🏢 {{ Auth::user()->company->name ?? 'Company' }}</span>
                            <span class="bg-indigo-800 text-indigo-100 text-[10px] px-1.5 py-0.5 rounded font-bold">STAFF</span>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-mono font-semibold bg-gray-800 text-gray-300 border border-gray-700" title="Personal Mode">
                            <span class="w-2 h-2 rounded-full bg-green-400"></span>
                            <span>👤 Personal</span>
                        </div>
                    @endif

                    <a href="/onboarding" class="text-xs text-gray-400 hover:text-gray-200 border border-gray-700 hover:bg-gray-800 px-2.5 py-1.5 rounded transition" title="Switch Mode">
                        Switch Mode
                    </a>

                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="bg-gray-800 text-gray-300 hover:text-white hover:bg-gray-700 border border-gray-700 px-3 py-1.5 rounded text-xs sm:text-sm transition">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    @auth
        <!-- Session Flash Message Banner -->
        @if(session('success'))
            <div class="max-w-6xl mx-auto px-4 mt-4">
                <div class="bg-green-950/80 border border-green-700 text-green-300 px-4 py-3 rounded-xl flex items-center justify-between gap-3 text-sm shadow-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-200">&times;</button>
                </div>
            </div>
        @endif

        @if($errors->has('member_error'))
            <div class="max-w-6xl mx-auto px-4 mt-4">
                <div class="bg-red-950/80 border border-red-700 text-red-300 px-4 py-3 rounded-xl flex items-center justify-between gap-3 text-sm shadow-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ $errors->first('member_error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-200">&times;</button>
                </div>
            </div>
        @endif

        <!-- Alarm / Due Date Notification Modal Banner -->
        <div id="alarm-banner-container" class="max-w-6xl mx-auto px-4 mt-3 hidden"></div>
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

    <main class="max-w-6xl mx-auto mt-6 p-4">
        {{ $slot }}
    </main>
</body>
</html>