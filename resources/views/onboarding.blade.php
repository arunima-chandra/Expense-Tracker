<x-layout class="bg-black">
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="text-center mb-10">
            <span class="px-3 py-1 rounded-full text-xs font-mono font-semibold bg-green-950 text-green-400 border border-green-800/80 uppercase tracking-wider">
                Setup & Workspace Mode
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-100 font-mono mt-3">
                Choose Your Expense Tracker Mode
            </h1>
            <p class="text-gray-400 text-sm sm:text-base max-w-xl mx-auto mt-2">
                Select how you want to manage finances. You can run solo in Personal mode or collaborate with your team in Company mode.
            </p>
        </div>

        @if ($errors->any())
            <div class="max-w-2xl mx-auto bg-red-950/60 border border-red-800 text-red-300 p-4 rounded-xl mb-8 flex items-start gap-3 text-sm">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <span class="font-bold">Error:</span> {{ $errors->first() }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Option 1: Personal Use -->
            <div class="bg-gray-900 border border-gray-800 hover:border-green-500/50 rounded-2xl p-6 sm:p-8 flex flex-col justify-between transition-all duration-300 shadow-xl group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-green-950/80 border border-green-800/60 text-green-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            👤
                        </div>
                        <span class="px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-gray-800 text-gray-300 border border-gray-700">
                            Single User
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-100 font-mono mb-2">Personal Use</h2>
                    <p class="text-sm text-gray-400 mb-6 leading-relaxed">
                        Dedicated solo finance tracker. Log your personal income, expenses, accounts, and bill reminders with 100% data isolation.
                    </p>

                    <ul class="space-y-2.5 text-xs text-gray-300 mb-8">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Private accounts & running balance charts</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Personal monthly & yearly expense reports</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Due date alarms and recurring bill reminders</span>
                        </li>
                    </ul>
                </div>

                <form method="POST" action="/onboarding/personal">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-500 text-white font-medium py-3 px-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-green-950">
                        <span>Continue as Personal User</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>

            <!-- Option 2: Company Use -->
            <div class="bg-gray-900 border border-gray-800 hover:border-blue-500/50 rounded-2xl p-6 sm:p-8 flex flex-col justify-between transition-all duration-300 shadow-xl group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-950/80 border border-blue-800/60 text-blue-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                            🏢
                        </div>
                        <span class="px-2.5 py-1 rounded-md text-xs font-mono font-semibold bg-blue-950/70 text-blue-400 border border-blue-800/60">
                            Multi-User & Roles
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-100 font-mono mb-2">Company Use</h2>
                    <p class="text-sm text-gray-400 mb-6 leading-relaxed">
                        Team finance management with role permissions. Admins oversee organization totals and employees submit their own expenses.
                    </p>

                    <!-- Sub Choice Selector -->
                    <div class="bg-gray-950 p-1 rounded-xl border border-gray-800 grid grid-cols-2 gap-1 mb-6">
                        <button type="button" id="tab-create-btn" onclick="toggleCompanyTab('create')" class="py-2 text-xs font-semibold rounded-lg transition bg-blue-600 text-white shadow">
                            Create Company
                        </button>
                        <button type="button" id="tab-join-btn" onclick="toggleCompanyTab('join')" class="py-2 text-xs font-semibold rounded-lg transition text-gray-400 hover:text-gray-200">
                            Join with Code
                        </button>
                    </div>

                    <!-- Sub Form: Create Company (Admin) -->
                    <div id="company-create-panel" class="space-y-4 mb-6">
                        <p class="text-xs text-blue-400/90 font-mono bg-blue-950/40 p-2.5 rounded-lg border border-blue-900/50">
                            👑 You will be registered as the <strong>Company Admin</strong> and receive a reusable employee invite code.
                        </p>
                        <form method="POST" action="/onboarding/company/create" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-mono text-gray-400 mb-1">Company / Organization Name</label>
                                <input type="text" name="name" placeholder="e.g. Acme Innovations Corp" required value="{{ old('name') }}" class="w-full bg-gray-800 text-gray-100 placeholder-gray-500 border border-gray-700 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-medium py-3 px-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-blue-950">
                                <span>Create Company & Become Admin</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                    </div>

                    <!-- Sub Form: Join Company (Employee) -->
                    <div id="company-join-panel" class="space-y-4 mb-6 hidden">
                        <p class="text-xs text-gray-300 font-mono bg-gray-950 p-2.5 rounded-lg border border-gray-800">
                            💼 Join an existing company as an <strong>Employee</strong> using the invite code provided by your admin.
                        </p>
                        <form method="POST" action="/onboarding/company/join" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-mono text-gray-400 mb-1">Company 8-Character Invite Code</label>
                                <input type="text" name="invite_code" placeholder="e.g. ABC12XYZ" required maxlength="16" value="{{ old('invite_code') }}" class="w-full bg-gray-800 text-gray-100 uppercase tracking-widest font-mono placeholder-gray-500 border border-gray-700 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:border-blue-500">
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-medium py-3 px-4 rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-indigo-950">
                                <span>Join Company as Employee</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCompanyTab(tab) {
            const createBtn = document.getElementById('tab-create-btn');
            const joinBtn = document.getElementById('tab-join-btn');
            const createPanel = document.getElementById('company-create-panel');
            const joinPanel = document.getElementById('company-join-panel');

            if (tab === 'create') {
                createBtn.className = 'py-2 text-xs font-semibold rounded-lg transition bg-blue-600 text-white shadow';
                joinBtn.className = 'py-2 text-xs font-semibold rounded-lg transition text-gray-400 hover:text-gray-200';
                createPanel.classList.remove('hidden');
                joinPanel.classList.add('hidden');
            } else {
                joinBtn.className = 'py-2 text-xs font-semibold rounded-lg transition bg-indigo-600 text-white shadow';
                createBtn.className = 'py-2 text-xs font-semibold rounded-lg transition text-gray-400 hover:text-gray-200';
                joinPanel.classList.remove('hidden');
                createPanel.classList.add('hidden');
            }
        }
    </script>
</x-layout>
