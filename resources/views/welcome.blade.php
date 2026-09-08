<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker — Take Control of Your Money</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Playfair Display', serif;
            background: #0a0a0a;
            color: #e0e0e0;
        }
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: #0a0a0a;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: "";
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(46,213,115,0.08), transparent 70%);
            top: -100px; right: -100px;
            border-radius: 50%;
        }
        .badge-soft {
            background: rgba(46,213,115,0.1);
            color: #2ed573;
            border: 1px solid rgba(46,213,115,0.4);
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
        }
        .gradient-text {
            color: #2ed573;
        }
        .btn-glow {
            background: #2ed573;
            border: none;
            color: #0a0a0a;
            font-weight: 700;
            padding: 12px 32px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .btn-glow:hover {
            background: #26c065;
            color: #0a0a0a;
            box-shadow: 0 0 20px rgba(46,213,115,0.4);
            transform: translateY(-2px);
        }
        .btn-outline-glow {
            border: 1px solid rgba(255,255,255,0.2);
            color: #e0e0e0;
            padding: 12px 32px;
            border-radius: 6px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-outline-glow:hover {
            background: rgba(255,255,255,0.05);
            color: white;
            border-color: rgba(46,213,115,0.5);
        }
        .feature-card {
            background: #111318;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 32px;
            height: 100%;
            transition: 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            border-color: rgba(46,213,115,0.4);
        }
        .feature-icon {
            width: 56px; height: 56px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
            background: rgba(46,213,115,0.1);
            color: #2ed573;
        }
        .section-dark { background: #111318; padding: 90px 0; }
        .step-number {
            width: 40px; height: 40px;
            border-radius: 6px;
            background: #2ed573;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
            color: #0a0a0a;
            flex-shrink: 0;
        }
        .navbar-brand { font-weight: 700; letter-spacing: -0.5px; color: #e0e0e0 !important; }
    </style>
</head>
<body>

    <!-- Nav -->
    <nav class="navbar navbar-dark px-4 px-md-5 py-3 position-absolute w-100" style="z-index: 10;">
        <a class="navbar-brand fs-4" href="/">
            <i class="bi bi-wallet2 gradient-text"></i> Expense Tracker
        </a>
        <div>
            <a href="/login" class="btn btn-outline-glow btn-sm me-2">Login</a>
            <a href="/register" class="btn btn-glow btn-sm">Register</a>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero px-4 px-md-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="badge-soft mb-4 d-inline-block">
                        <i class="bi bi-stars"></i> Your money, finally organized
                    </span>
                    <h1 class="display-4 fw-bold mb-4">
                        Track every rupee.<br>
                        <span class="gradient-text">Never miss a bill again.</span>
                    </h1>
                    <p class="fs-5 text-white-50 mb-4" style="max-width: 560px;">
                        Expense Tracker is a simple, personal finance app that logs your income and expenses,
                        reminds you before your bills are due, and shows you exactly where your money goes —
                        every month, every year.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="/register" class="btn btn-glow">Get Started Free <i class="bi bi-arrow-right"></i></a>
                        <a href="#how-it-works" class="btn btn-outline-glow">See How It Works</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="section-dark" id="features">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge-soft mb-3 d-inline-block">Features</span>
                <h2 class="fw-bold">Everything you need. Nothing you don't.</h2>
                <p class="text-white-50">Four simple tools working together to keep your money in check.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-piggy-bank"></i>
                        </div>
                        <h5 class="fw-bold">Accounts & Transactions</h5>
                        <p class="text-white-50 mb-0">
                            Create separate accounts (cash, bank, wallet) and log every income or expense
                            with a category, amount, and date — see your live balance instantly.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <h5 class="fw-bold">Bill Reminders</h5>
                        <p class="text-white-50 mb-0">
                            Add rent, EMI, salary day, or any recurring bill. Choose to get an in-app
                            notification, a loud alarm, or both — the day it's due.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bar-chart-fill"></i>
                        </div>
                        <h5 class="fw-bold">Monthly & Yearly Reports</h5>
                        <p class="text-white-50 mb-0">
                            Browse a dedicated page for every month, or zoom out to see your whole year
                            broken down income vs. expense, month by month.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h5 class="fw-bold">Private & Secure</h5>
                        <p class="text-white-50 mb-0">
                            Your data is yours alone — every account and transaction is locked to your
                            login, with every access attempt logged for security.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section class="py-5" id="how-it-works" style="padding: 90px 0;">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge-soft mb-3 d-inline-block">How It Works</span>
                <h2 class="fw-bold">Four steps. That's it.</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="d-flex gap-3 mb-4">
                        <div class="step-number">1</div>
                        <div>
                            <h6 class="fw-bold mb-1">Create an account</h6>
                            <p class="text-white-50 mb-0">Sign up in seconds, then add one or more accounts — like "Cash" or "Bank Account" — to track separately.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3 mb-4">
                        <div class="step-number">2</div>
                        <div>
                            <h6 class="fw-bold mb-1">Log your transactions</h6>
                            <p class="text-white-50 mb-0">Every time you spend or earn, add it as income or expense with a category — takes 10 seconds.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3 mb-4">
                        <div class="step-number">3</div>
                        <div>
                            <h6 class="fw-bold mb-1">Set your reminders</h6>
                            <p class="text-white-50 mb-0">Add your rent, bills, and EMI dates once — the app remembers so you don't have to.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-3 mb-4">
                        <div class="step-number">4</div>
                        <div>
                            <h6 class="fw-bold mb-1">Check your reports</h6>
                            <p class="text-white-50 mb-0">Open any month or year to instantly see where your money went, category by category.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section-dark text-center">
        <div class="container">
            <h2 class="fw-bold mb-3">Ready to take control?</h2>
            <p class="text-white-50 mb-4">It's free, it's yours, and it takes less than a minute to start.</p>
            <a href="/register" class="btn btn-glow btn-lg me-2">Create Free Account</a>
            <a href="/login" class="btn btn-outline-glow btn-lg">I already have an account</a>
        </div>
    </section>

    <footer class="text-center py-4 text-white-50" style="background:#0a0a0a;">
        <small>&copy; 2026 Expense Tracker — built while learning Laravel.</small>
    </footer>

</body>
</html>