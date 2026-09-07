<x-layout class="bg-black">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-100 font-mono">My Accounts</h2>
            <p class="text-sm text-gray-400 mt-1">Manage your financial accounts and view transactions</p>
        </div>
        <a href="/dashboard" class="bg-gray-800 text-gray-300 hover:text-white hover:bg-gray-700 border border-gray-700 px-3 py-1.5 rounded text-sm transition flex items-center gap-1.5">
            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span>View Dashboard Charts</span>
        </a>
    </div>

    <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg mb-6">
        <h3 class="text-lg font-semibold text-gray-100 mb-4">Add Account</h3>
        <form method="POST" action="/accounts" class="flex flex-wrap sm:flex-nowrap gap-2">
            @csrf
            <input type="text" name="name" placeholder="Account Name" required class="bg-gray-800 text-gray-100 placeholder-gray-500 border border-gray-700 rounded px-3 py-1.5 flex-1 focus:outline-none focus:border-green-500">
            <select name="type" required class="bg-gray-800 text-gray-100 border border-gray-700 rounded px-3 py-1.5 focus:outline-none focus:border-green-500">
                <option value="bank">Bank</option>
                <option value="cash">Cash</option>
                <option value="credit_card">Credit Card</option>
            </select>
            <button type="submit" class="bg-green-600 text-white px-4 py-1.5 rounded hover:bg-green-500 font-medium transition">Add Account</button>
        </form>
    </div>

    @if($accounts->isEmpty())
        <div class="bg-gray-900 border border-gray-800 p-8 rounded-lg text-center text-gray-400">
            <p class="text-base">No accounts added yet. Use the form above to add your first account!</p>
        </div>
    @endif

    @foreach ($accounts as $account)
        <div class="bg-gray-900 border border-gray-800 p-6 rounded-lg mb-6">
            <div class="flex justify-between items-center mb-4 gap-4">
                <div id="account-info-{{ $account->id }}" class="flex-1">
                    <p class="text-lg font-semibold text-gray-100">{{ $account->name }}</p>
                    <p class="text-sm text-gray-400 font-mono uppercase tracking-wider">{{ $account->type }}</p>
                </div>

                <form id="account-edit-form-{{ $account->id }}" method="POST" action="/accounts/{{ $account->id }}" class="hidden flex-1 flex flex-wrap gap-2 items-center">
                    @csrf
                    @method('PUT')
                    <input type="text" name="name" value="{{ $account->name }}" required class="bg-gray-800 text-gray-100 placeholder-gray-500 border border-gray-700 rounded px-2.5 py-1 text-sm focus:outline-none focus:border-green-500 flex-1 min-w-[120px]">
                    <select name="type" required class="bg-gray-800 text-gray-100 border border-gray-700 rounded px-2.5 py-1 text-sm focus:outline-none focus:border-green-500">
                        <option value="bank" @selected($account->type === 'bank')>Bank</option>
                        <option value="cash" @selected($account->type === 'cash')>Cash</option>
                        <option value="credit_card" @selected($account->type === 'credit_card')>Credit Card</option>
                    </select>
                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-500 font-medium transition">Save</button>
                    <button type="button" onclick="toggleAccountEdit({{ $account->id }})" class="bg-gray-800 text-gray-400 hover:text-gray-200 border border-gray-700 px-3 py-1 rounded text-sm transition">Cancel</button>
                </form>

                <div class="flex items-center gap-4">
                    <p class="text-2xl font-bold font-mono {{ $account->balance >= 0 ? 'text-green-400' : 'text-red-400' }}">
                        ₹{{ number_format($account->balance, 2) }}
                    </p>
                    <div class="flex items-center gap-2">
                        <button type="button" id="edit-btn-{{ $account->id }}" onclick="toggleAccountEdit({{ $account->id }})" class="text-blue-400 hover:text-blue-300 hover:underline text-sm transition">Edit</button>
                        <form method="POST" action="/accounts/{{ $account->id }}" onsubmit="return confirm('Are you sure? This will also delete all its transactions.');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 hover:underline text-sm transition">Delete</button>
                        </form>
                    </div>
                </div>
            </div>

            <form method="POST" action="/accounts/{{ $account->id }}/transactions" class="flex flex-wrap sm:flex-nowrap gap-2 mb-4">
                @csrf
                <select name="type" class="bg-gray-800 text-gray-100 border border-gray-700 rounded px-2 py-1 focus:outline-none focus:border-green-500">
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
                <input type="number" step="0.01" name="amount" placeholder="Amount" required class="bg-gray-800 text-gray-100 placeholder-gray-500 border border-gray-700 rounded px-2 py-1 w-24 focus:outline-none focus:border-green-500">
                <input type="text" name="category" placeholder="Category" class="bg-gray-800 text-gray-100 placeholder-gray-500 border border-gray-700 rounded px-2 py-1 flex-1 focus:outline-none focus:border-green-500">
                <input type="date" name="date" required class="bg-gray-800 text-gray-100 border border-gray-700 rounded px-2 py-1 focus:outline-none focus:border-green-500 [color-scheme:dark]">
                <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-500 font-medium transition">Add</button>
            </form>

            @if($account->transactions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        @foreach ($account->transactions as $t)
                            <tr class="border-t border-gray-800 hover:bg-gray-800/50 transition">
                                <td class="py-2.5 text-gray-400 font-mono">{{ $t->date }}</td>
                                <td class="text-gray-300">{{ $t->category }}</td>
                                <td class="font-mono {{ $t->type === 'income' ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $t->type === 'income' ? '+' : '-' }}₹{{ number_format($t->amount, 2) }}
                                </td>
                                <td class="text-right">
                                    <form method="POST" action="/accounts/{{ $account->id }}/transactions/{{ $t->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-400 hover:text-red-300 hover:underline transition">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @else
                <p class="text-xs text-gray-500 italic">No transactions yet for this account.</p>
            @endif
        </div>
    @endforeach

    <script>
        function toggleAccountEdit(id) {
            const info = document.getElementById('account-info-' + id);
            const form = document.getElementById('account-edit-form-' + id);
            const editBtn = document.getElementById('edit-btn-' + id);
            if (!info || !form) return;

            if (form.classList.contains('hidden')) {
                info.classList.add('hidden');
                form.classList.remove('hidden');
                if (editBtn) editBtn.classList.add('hidden');
                const nameInput = form.querySelector('input[name="name"]');
                if (nameInput) nameInput.focus();
            } else {
                form.classList.add('hidden');
                info.classList.remove('hidden');
                if (editBtn) editBtn.classList.remove('hidden');
            }
        }
    </script>
</x-layout>
