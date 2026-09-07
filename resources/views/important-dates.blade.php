<x-layout class="bg-black">
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-100 font-mono flex items-center gap-2">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Important Dates & Reminders</span>
                </h2>
                <p class="text-sm text-gray-400 mt-1">Manage recurring bills, EMIs, salary days, and scheduled alarms</p>
            </div>
            <button onclick="document.getElementById('add-reminder-modal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Reminder</span>
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-950/80 border border-green-700 text-green-300 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Reminders List -->
        @if($dates->isEmpty())
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-12 text-center text-gray-400">
                <div class="w-16 h-16 bg-gray-800 text-green-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-100 font-mono mb-2">No Reminders Created Yet</h3>
                <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">Keep track of upcoming electricity bills, rent payments, EMIs, or salary days with browser notifications and loud alarms.</p>
                <button onclick="document.getElementById('add-reminder-modal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Create First Reminder
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($dates as $item)
                    @php
                        $isDueToday = false;
                        if ($item->is_recurring) {
                            $isDueToday = (int) date('j') === (int) $item->due_day;
                        } else if ($item->due_date) {
                            $isDueToday = $item->due_date->toDateString() === date('Y-m-d');
                        }
                    @endphp
                    <div class="bg-gray-900 border {{ $isDueToday ? 'border-amber-500/80 bg-gray-900/90' : 'border-gray-800' }} rounded-xl p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start gap-2 mb-3">
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-base font-bold text-gray-100">{{ $item->title }}</h4>
                                        <span class="text-xs px-2 py-0.5 rounded font-mono uppercase {{ $item->type === 'salary' ? 'bg-green-950 text-green-400 border border-green-800' : 'bg-gray-800 text-gray-400 border border-gray-700' }}">
                                            {{ $item->type }}
                                        </span>
                                        @if($isDueToday)
                                            <span class="text-xs px-2 py-0.5 rounded font-bold font-mono uppercase bg-amber-950 text-amber-400 border border-amber-600 animate-pulse">
                                                🚨 Due Today!
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 font-mono mt-1">
                                        @if($item->is_recurring)
                                            🔄 Recurring on Day {{ $item->due_day }} of every month
                                        @else
                                            📅 Specific Date: {{ $item->due_date?->format('d M Y') }}
                                        @endif
                                    </p>
                                </div>

                                @if($item->amount !== null)
                                    <div class="text-right">
                                        <p class="text-base font-bold font-mono text-gray-100">₹{{ number_format((float) $item->amount, 2) }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
                                <span class="flex items-center gap-1 bg-gray-800/80 px-2.5 py-1 rounded-md border border-gray-700/60">
                                    @if($item->reminder_method === 'alarm')
                                        🔊 Alarm Alert
                                    @elseif($item->reminder_method === 'both')
                                        🔔 Notification + 🔊 Alarm
                                    @else
                                        🔔 In-App Notification
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="flex justify-end items-center gap-3 pt-3 border-t border-gray-800/80 text-xs">
                            <form method="POST" action="/important-dates/{{ $item->id }}" onsubmit="return confirm('Delete this reminder?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 hover:underline">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Add Reminder Modal -->
    <div id="add-reminder-modal" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-gray-900 border border-gray-800 rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-lg font-bold text-gray-100 font-mono">Create New Reminder</h3>
                <button onclick="document.getElementById('add-reminder-modal').classList.add('hidden')" class="text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="/important-dates" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-gray-400 mb-1.5">Reminder Title *</label>
                    <input type="text" name="title" placeholder="e.g. Electricity Bill, House Rent, Car EMI, Salary" required class="w-full bg-gray-800 text-gray-100 border border-gray-700 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-green-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-gray-400 mb-1.5">Type *</label>
                        <select name="type" required class="w-full bg-gray-800 text-gray-100 border border-gray-700 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-green-500">
                            <option value="bill">Bill</option>
                            <option value="rent">Rent</option>
                            <option value="emi">EMI</option>
                            <option value="salary">Salary</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-mono uppercase tracking-wider text-gray-400 mb-1.5">Amount (₹)</label>
                        <input type="number" step="0.01" name="amount" placeholder="Optional amount" class="w-full bg-gray-800 text-gray-100 border border-gray-700 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-green-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-mono uppercase tracking-wider text-gray-400 mb-1.5">Reminder Method *</label>
                    <select name="reminder_method" required class="w-full bg-gray-800 text-gray-100 border border-gray-700 rounded-lg px-3.5 py-2 text-sm focus:outline-none focus:border-green-500">
                        <option value="notification">In-App Notification</option>
                        <option value="alarm">Loud Alarm Alert (Frontend)</option>
                        <option value="both">Both (Notification + Alarm)</option>
                    </select>
                </div>

                <!-- Frequency Selection -->
                <div class="bg-gray-800/60 p-4 rounded-xl border border-gray-800 space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" id="is_recurring_checkbox" name="is_recurring" value="1" checked onchange="toggleRecurringOptions(this.checked)" class="rounded border-gray-700 text-green-600 focus:ring-green-500">
                        <span class="text-sm font-medium text-gray-200">Recurring Monthly Reminder</span>
                    </label>

                    <div id="recurring-day-field">
                        <label class="block text-xs text-gray-400 mb-1">Day of the Month (1–31) *</label>
                        <input type="number" name="due_day" min="1" max="31" value="1" class="w-full bg-gray-800 text-gray-100 border border-gray-700 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-green-500">
                    </div>

                    <div id="specific-date-field" class="hidden">
                        <label class="block text-xs text-gray-400 mb-1">Specific One-Time Date *</label>
                        <input type="date" name="due_date" class="w-full bg-gray-800 text-gray-100 border border-gray-700 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:border-green-500 [color-scheme:dark]">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('add-reminder-modal').classList.add('hidden')" class="bg-gray-800 hover:bg-gray-700 text-gray-300 px-4 py-2 rounded-lg text-sm transition">Cancel</button>
                    <button type="submit" class="bg-green-600 hover:bg-green-500 text-white font-medium px-5 py-2 rounded-lg text-sm transition">Save Reminder</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleRecurringOptions(isRecurring) {
            const recurringField = document.getElementById('recurring-day-field');
            const specificField = document.getElementById('specific-date-field');

            if (isRecurring) {
                recurringField.classList.remove('hidden');
                specificField.classList.add('hidden');
                recurringField.querySelector('input').setAttribute('required', 'required');
                specificField.querySelector('input').removeAttribute('required');
            } else {
                recurringField.classList.add('hidden');
                specificField.classList.remove('hidden');
                specificField.querySelector('input').setAttribute('required', 'required');
                recurringField.querySelector('input').removeAttribute('required');
            }
        }
    </script>
</x-layout>
