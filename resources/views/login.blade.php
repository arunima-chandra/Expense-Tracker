<x-layout>
    <div class="max-w-sm mx-auto bg-gray-900 border border-gray-800 p-8 rounded-lg mt-12">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-100 font-mono">Login</h2>

        @if ($errors->any())
            <div class="bg-red-950/50 border border-red-800 text-red-400 p-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <input type="email" name="email" placeholder="Email" required class="bg-gray-800 text-gray-100 placeholder-gray-500 border border-gray-700 rounded w-full px-3 py-2 mb-3 focus:outline-none focus:border-green-500">
            <input type="password" name="password" placeholder="Password" required class="bg-gray-800 text-gray-100 placeholder-gray-500 border border-gray-700 rounded w-full px-3 py-2 mb-4 focus:outline-none focus:border-green-500">
            <button type="submit" class="bg-green-600 text-white w-full py-2 rounded hover:bg-green-500 font-medium transition">Login</button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-400">
            No account? <a href="/register" class="text-green-400 hover:underline">Register</a>
        </p>
    </div>

    <div class="fixed inset-0 pointer-events-none -z-10 opacity-30 overflow-hidden" aria-hidden="true">
        <canvas id="loginBgCandleChart" class="w-full h-full" style="filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.45)) drop-shadow(0 0 18px rgba(99, 102, 241, 0.35));"></canvas>
        <svg class="absolute inset-0 w-full h-full pointer-events-none" style="filter: drop-shadow(0 0 8px #06b6d4) drop-shadow(0 0 18px rgba(6, 182, 212, 0.75));">
            <line x1="2%" y1="85%" x2="98%" y2="15%" stroke="#22d3ee" stroke-width="2" stroke-dasharray="6 4" opacity="0.8" />
        </svg>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('loginBgCandleChart');
            if (!ctx) return;

            // Generate initial ~28 decorative OHLC points with cool-tone styling
            const count = 28;
            const now = Date.now();
            const dayMs = 86400000;
            const candleData = [];
            let price = 500;

            for (let i = 0; i < count; i++) {
                const x = now - (count - i) * dayMs;
                const open = price;
                const isUp = i % 2 === 0 ? Math.random() > 0.45 : Math.random() < 0.55;
                const delta = (Math.random() * 22 + 5) * (isUp ? 1 : -1);
                const close = Math.max(50, open + delta);
                const high = Math.max(open, close) + Math.random() * 12 + 2;
                const low = Math.min(open, close) - Math.random() * 12 - 2;
                price = close;
                candleData.push({ x, o: open, h: high, l: low, c: close });
            }

            const chart = new Chart(ctx, {
                type: 'candlestick',
                data: {
                    datasets: [{
                        label: 'Market Ambient',
                        data: candleData,
                        color: {
                            up: '#06b6d4',
                            down: '#818cf8',
                            unchanged: '#64748b',
                        },
                        borderColor: {
                            up: '#22d3ee',
                            down: '#6366f1',
                            unchanged: '#94a3b8',
                        },
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1200,
                        easing: 'easeInOutQuart',
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false },
                    },
                    scales: {
                        x: {
                            type: 'timeseries',
                            display: false,
                            grid: { display: false },
                        },
                        y: {
                            display: false,
                            grid: { display: false },
                        },
                    },
                }
            });

            // Live continuous animation: shift dataset left and push new candle every 1.8s
            const tickerInterval = setInterval(() => {
                if (candleData.length === 0) return;
                const last = candleData[candleData.length - 1];
                const nextX = last.x + dayMs;
                const open = last.c;
                const isUp = Math.random() > 0.48;
                const delta = (Math.random() * 20 + 4) * (isUp ? 1 : -1);
                const close = Math.max(50, open + delta);
                const high = Math.max(open, close) + Math.random() * 12 + 2;
                const low = Math.min(open, close) - Math.random() * 12 - 2;

                candleData.shift();
                candleData.push({ x: nextX, o: open, h: high, l: low, c: close });
                chart.update('none');
            }, 1800);

            window.addEventListener('beforeunload', () => clearInterval(tickerInterval));
            window.addEventListener('pagehide', () => clearInterval(tickerInterval));
        });
    </script>
</x-layout>