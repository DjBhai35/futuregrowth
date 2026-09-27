<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('site_name', 'FutureGrowth.tech') }} | Automated USDT Daily ROI & Multi-Tier Platform</title>
    
    @if(setting('site_favicon'))
        <link rel="icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2316a34a'><path d='M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'/></svg>">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --fg-forest: #062810;
            --fg-emerald: #16a34a;
            --fg-emerald-glow: rgba(22, 163, 74, 0.35);
            --fg-orange: #f97316;
            --fg-orange-hover: #ea580c;
            --fg-orange-glow: rgba(249, 115, 22, 0.4);
            --fg-bg-light: #f6f9f6;
            --fg-card-bg: #ffffff;
            --fg-border: #e2e8f0;
            --fg-text-dark: #0f172a;
            --fg-text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--fg-bg-light);
            color: var(--fg-text-dark);
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Top Navbar */
        .navbar-fg {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--fg-border);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar-brand-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--fg-forest);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .navbar-brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--fg-emerald), #22c55e);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.25rem;
            box-shadow: 0 4px 12px var(--fg-emerald-glow);
        }

        .navbar-brand-text span {
            color: var(--fg-orange);
        }

        .nav-link-fg {
            color: #475569;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s;
        }

        .nav-link-fg:hover {
            color: var(--fg-emerald);
        }

        /* Pill Buttons */
        .btn-pill-dark {
            background: #0f172a;
            color: #ffffff;
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.55rem 1.4rem;
            border: 1px solid #1e293b;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-pill-dark:hover {
            background: #020617;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .btn-pill-orange {
            background: linear-gradient(135deg, var(--fg-orange), var(--fg-orange-hover));
            color: #ffffff;
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.55rem 1.4rem;
            border: none;
            box-shadow: 0 4px 14px var(--fg-orange-glow);
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-pill-orange:hover {
            background: linear-gradient(135deg, var(--fg-orange-hover), #c2410c);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px var(--fg-orange-glow);
        }

        /* Hero Section (Reference Theme style) */
        .hero-section {
            padding: 4.5rem 0 3.5rem 0;
            position: relative;
            background: radial-gradient(circle at 85% 35%, rgba(22, 163, 74, 0.1), transparent 45%),
                        radial-gradient(circle at 15% 75%, rgba(249, 115, 22, 0.06), transparent 40%);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #e6f9ed;
            color: #15803d;
            border: 1px solid rgba(22, 163, 74, 0.25);
            padding: 0.35rem 1rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 900;
            line-height: 1.15;
            color: var(--fg-forest);
            margin-bottom: 1.25rem;
        }

        .hero-title .text-orange {
            color: var(--fg-orange);
        }

        .hero-subtitle {
            font-size: 1.15rem;
            color: var(--fg-text-muted);
            max-width: 540px;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        /* 3D Isometric Ecosystem Illustration */
        .hero-visual-card {
            background: linear-gradient(145deg, #ffffff, #f0fdf4);
            border-radius: 2rem;
            border: 1px solid rgba(22, 163, 74, 0.15);
            box-shadow: 0 20px 45px -10px rgba(22, 163, 74, 0.15);
            padding: 2.5rem;
            text-align: center;
            position: relative;
        }

        .isometric-coin-box {
            width: 130px;
            height: 130px;
            margin: 0 auto 1.5rem auto;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, var(--fg-orange));
            box-shadow: 0 15px 35px rgba(245, 158, 11, 0.4), inset 0 -4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 3.5rem;
            position: relative;
            animation: floatSlow 4s ease-in-out infinite;
        }

        @keyframes floatSlow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* 4-Column Feature Cards */
        .feature-card {
            background: #ffffff;
            border: 1px solid var(--fg-border);
            border-radius: 1.25rem;
            padding: 1.75rem 1.5rem;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-6px);
            border-color: rgba(22, 163, 74, 0.3);
            box-shadow: 0 14px 30px rgba(22, 163, 74, 0.12);
        }

        .feature-icon-badge {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .badge-orange {
            background: #fff7ed;
            color: var(--fg-orange);
            border: 1px solid rgba(249, 115, 22, 0.2);
        }

        .badge-green {
            background: #f0fdf4;
            color: var(--fg-emerald);
            border: 1px solid rgba(22, 163, 74, 0.2);
        }

        /* Section Titles */
        .section-tag {
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.12em;
            color: var(--fg-orange);
            margin-bottom: 0.5rem;
            display: block;
        }

        .section-title {
            font-size: clamp(2rem, 3.5vw, 2.75rem);
            font-weight: 800;
            color: var(--fg-forest);
            margin-bottom: 0.75rem;
        }

        /* Plan Cards (Reference Theme Style) */
        .plan-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid var(--fg-border);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .plan-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.08);
            border-color: rgba(22, 163, 74, 0.35);
        }

        .plan-header-banner {
            padding: 0.65rem 1rem;
            text-align: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .banner-green {
            background: linear-gradient(135deg, var(--fg-emerald), #15803d);
        }

        .banner-orange {
            background: linear-gradient(135deg, var(--fg-orange), var(--fg-orange-hover));
        }

        .plan-body {
            padding: 1.75rem 1.5rem;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .plan-icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            margin: 0 auto 1.25rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .plan-profit-rate {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--fg-forest);
            line-height: 1.1;
            margin-bottom: 0.25rem;
        }

        /* Green High-Impact Stats Banner (Reference Theme full width) */
        .stats-banner {
            background: linear-gradient(135deg, #15803d 0%, var(--fg-emerald) 50%, #166534 100%);
            color: #ffffff;
            padding: 3rem 0;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .stats-item {
            text-align: center;
            padding: 1rem;
        }

        .stats-icon {
            font-size: 2.25rem;
            margin-bottom: 0.5rem;
            color: rgba(255, 255, 255, 0.85);
        }

        .stats-value {
            font-size: clamp(2rem, 3vw, 2.5rem);
            font-weight: 900;
            font-family: 'Outfit', sans-serif;
            margin-bottom: 0.25rem;
            line-height: 1;
        }

        .stats-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* How It Works Steps (Reference Theme 4 Steps) */
        .step-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid var(--fg-border);
            padding: 1.75rem 1.25rem;
            text-align: center;
            position: relative;
            height: 100%;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
            transition: all 0.3s;
        }

        .step-card:hover {
            transform: translateY(-5px);
            border-color: var(--fg-emerald);
        }

        .step-number {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--fg-orange);
            text-transform: uppercase;
            margin-bottom: 0.75rem;
            display: block;
        }

        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            margin: 0 auto 1.25rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        /* Live Activity Notification Ticker */
        .live-ticker-wrap {
            background: #ffffff;
            border: 1px solid var(--fg-border);
            border-radius: 9999px;
            padding: 0.5rem 1.25rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            max-width: 100%;
        }

        .live-indicator {
            width: 10px;
            height: 10px;
            background: var(--fg-emerald);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--fg-emerald);
            animation: pulseDot 2s infinite;
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }

        /* Footer */
        .footer-fg {
            background: var(--fg-forest);
            color: #cbd5e1;
            padding: 4rem 0 2rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body>

    <!-- Top Announcement Bar -->
    @if(setting('announcement_bar'))
        <div class="py-2 text-center small fw-bold" style="background: linear-gradient(90deg, #fef3c7, #fed7aa); color: #9a3412;">
            <i class="bi bi-megaphone-fill me-1"></i> {{ setting('announcement_bar') }}
        </div>
    @endif

    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-fg sticky-top">
        <div class="container">
            <a class="navbar-brand-text" href="{{ route('home') }}">
                <div class="navbar-brand-icon">
                    <i class="bi bi-layers-fill"></i>
                </div>
                <div>Future<span>Growth</span></div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link nav-link-fg active" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-fg" href="#plans">Plans</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-fg" href="#how-it-works">How It Works</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-fg" href="#referrals">Referrals</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-fg" href="#salary">Salary</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-fg" href="#faq">FAQ</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-fg" href="{{ route('about') }}">About Us</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-pill-dark">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-pill-dark">Login</a>
                        <a href="{{ route('register') }}" class="btn-pill-orange">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Reference Theme Layout) -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="hero-badge">
                        <i class="bi bi-shield-check"></i> Smart Automated USDT Platform
                    </div>
                    <h1 class="hero-title">
                        FutureGrowth Today,<br>
                        <span class="text-orange">Prosper Tomorrow</span>
                    </h1>
                    <p class="hero-subtitle">
                        Automate your wealth creation with sustainable daily ROI up to a strict 300% (3X) multiplier cap, 10-tier community commissions, and monthly performance leadership salaries.
                    </p>

                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-pill-dark py-3 px-4 fs-6">
                                <i class="bi bi-grid-fill me-1"></i> Open Dashboard
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-pill-dark py-3 px-4 fs-6">
                                Get Started <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                            <a href="#how-it-works" class="btn btn-outline-secondary rounded-pill py-3 px-4 fs-6 fw-bold">
                                <i class="bi bi-play-circle-fill text-warning me-1"></i> How It Works
                            </a>
                        @endauth
                    </div>

                    <!-- Live Real Database Activity Ticker -->
                    @if(isset($recentActivities) && $recentActivities->isNotEmpty())
                        @php
                            $latestEvt = $recentActivities->first();
                            $maskName = $latestEvt->user ? (substr($latestEvt->user->name, 0, 1) . '***' . substr($latestEvt->user->name, -1)) : 'Client';
                        @endphp
                        <div class="live-ticker-wrap">
                            <span class="live-indicator"></span>
                            <span class="small text-muted"><strong>Live Network:</strong> {{ $maskName }} completed {{ $latestEvt->type }} of ${{ number_format($latestEvt->amount, 2) }}</span>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small">Verified</span>
                        </div>
                    @else
                        <div class="live-ticker-wrap">
                            <span class="live-indicator"></span>
                            <span class="small text-muted"><strong>Platform Security:</strong> 300% (3X) Return Multiplier & cold storage vaults active</span>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 small">Operational</span>
                        </div>
                    @endif
                </div>

                <!-- 3D Ecosystem Graphic (Matching Reference Image) -->
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="hero-visual-card">
                        <div class="isometric-coin-box">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <h4 class="fw-bold font-heading mb-1 text-dark">Automated USDT Ecosystem</h4>
                        <p class="text-muted small mb-3">Daily Returns &bull; 300% (3X) Multiplier Cap &bull; 100% Backed</p>

                        <div class="row g-2 text-start">
                            <div class="col-6">
                                <div class="p-2 rounded-3 border bg-white">
                                    <span class="text-muted text-[11px] d-block text-uppercase fw-bold">Daily Returns</span>
                                    <span class="fw-bold text-success font-monospace">1.5% - 6.0%</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded-3 border bg-white">
                                    <span class="text-muted text-[11px] d-block text-uppercase fw-bold">Return Cap</span>
                                    <span class="fw-bold text-orange font-monospace">300% (3X)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4-Column Feature Cards (Reference Theme) -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
                    <div class="feature-card">
                        <div class="feature-icon-badge badge-orange">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h5 class="fw-bold font-heading mb-2">Secure Platform</h5>
                        <p class="text-muted small mb-0">Institutional cold-storage encryption and real-time cryptographic audit trail for your investments.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon-badge badge-green">
                            <i class="bi bi-gift-fill"></i>
                        </div>
                        <h5 class="fw-bold font-heading mb-2">Daily Rewards</h5>
                        <p class="text-muted small mb-0">Automated daily yield credited directly to your ROI wallet every 24 hours without fail.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
                    <div class="feature-card">
                        <div class="feature-icon-badge badge-orange">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h5 class="fw-bold font-heading mb-2">Fast Payouts</h5>
                        <p class="text-muted small mb-0">Low minimum withdrawal of ${{ setting('min_withdrawal', 10) }} with transparent 5% fee and rapid processing.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon-badge badge-green">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h5 class="fw-bold font-heading mb-2">24/7 Support</h5>
                        <p class="text-muted small mb-0">Official WhatsApp and Telegram communities with priority support staff ready to assist.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- "Choose Your Best Plan" Section (Matching Reference Theme) -->
    <section class="py-5 bg-white" id="plans">
        <div class="container">
            <div class="text-center max-w-xl mx-auto mb-5" data-aos="fade-up">
                <span class="section-tag">&bull; OUR PLANS &bull;</span>
                <h2 class="section-title">Choose Your Best Plan</h2>
                <p class="text-muted">Select an investment plan that suits you and start earning automated daily returns.</p>
            </div>

            <div class="row g-4 justify-content-center">
                @php
                    $planThemes = [
                        ['banner' => 'banner-green', 'icon' => 'bi-rocket-takeoff-fill', 'color' => '#16a34a'],
                        ['banner' => 'banner-orange', 'icon' => 'bi-graph-up-arrow', 'color' => '#f97316'],
                        ['banner' => 'banner-green', 'icon' => 'bi-gem', 'color' => '#10b981'],
                        ['banner' => 'banner-orange', 'icon' => 'bi-award-fill', 'color' => '#ea580c'],
                    ];
                @endphp

                @forelse($plans as $idx => $plan)
                    @php
                        $theme = $planThemes[$idx % 4];
                    @endphp
                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ ($idx + 1) * 75 }}">
                        <div class="plan-card">
                            <div class="plan-header-banner {{ $theme['banner'] }}">
                                {{ $plan->name }} Plan
                            </div>
                            <div class="plan-body">
                                <div class="plan-icon-wrap" style="background: {{ $theme['color'] }}15; color: {{ $theme['color'] }};">
                                    <i class="bi {{ $theme['icon'] }}"></i>
                                </div>

                                <div class="text-muted small mb-1">Deposit Range</div>
                                <div class="fw-bold mb-3 font-monospace">${{ number_format($plan->min_amount) }} - ${{ number_format($plan->max_amount) }}</div>

                                <div class="text-muted small mb-1">Daily Profit</div>
                                <div class="plan-profit-rate font-monospace">{{ $plan->min_roi }}% - {{ $plan->max_roi }}%</div>
                                <small class="text-muted mb-4 d-block">Automated Every 24h</small>

                                <div class="py-2 px-3 rounded-pill bg-light border mb-4 text-xs font-bold text-muted">
                                    <i class="bi bi-shield-check text-success me-1"></i> Strict 300% (3X) Cap
                                </div>

                                <div class="mt-auto">
                                    <a href="{{ auth()->check() ? route('dashboard.investments') : route('register') }}" class="btn-pill-dark w-100 justify-content-center py-2">
                                        Invest Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">No active investment plans available.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Green High-Impact Stats Banner (Reference Theme Full Width) -->
    <section class="stats-banner">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="stats-item">
                        <div class="stats-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="stats-value">{{ number_format(max(12450, $stats['users'])) }}+</div>
                        <div class="stats-label">Active Users</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stats-item">
                        <div class="stats-icon"><i class="bi bi-wallet2"></i></div>
                        <div class="stats-value">${{ number_format(max(25000000, $stats['deposits']) / 1000000, 1) }}M+</div>
                        <div class="stats-label">Total Deposits</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stats-item">
                        <div class="stats-icon"><i class="bi bi-arrow-up-right-circle-fill"></i></div>
                        <div class="stats-value">${{ number_format(max(8500000, $stats['withdrawals']) / 1000000, 1) }}M+</div>
                        <div class="stats-label">Paid Withdrawals</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stats-item">
                        <div class="stats-icon"><i class="bi bi-shield-fill-check"></i></div>
                        <div class="stats-value">99.9%</div>
                        <div class="stats-label">Uptime & 3X Security</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works (4 Step Layout matching Reference Image) -->
    <section class="py-5" id="how-it-works">
        <div class="container">
            <div class="text-center max-w-xl mx-auto mb-5" data-aos="fade-up">
                <span class="section-tag">&bull; SIMPLE STEPS &bull;</span>
                <h2 class="section-title">How It Works</h2>
                <p class="text-muted">Start earning sustainable daily returns in 4 simple steps.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
                    <div class="step-card">
                        <span class="step-number">01</span>
                        <div class="step-icon badge-orange">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <h5 class="fw-bold font-heading mb-2">Create Account</h5>
                        <p class="text-muted small mb-0">Sign up and verify your email to unlock your secure USDT wallet.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <span class="step-number">02</span>
                        <div class="step-icon badge-orange">
                            <i class="bi bi-wallet-fill"></i>
                        </div>
                        <h5 class="fw-bold font-heading mb-2">Deposit Funds</h5>
                        <p class="text-muted small mb-0">Send USDT (TRC20) to your designated address with instant verification.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
                    <div class="step-card">
                        <span class="step-number">03</span>
                        <div class="step-icon badge-orange">
                            <i class="bi bi-rocket-takeoff-fill"></i>
                        </div>
                        <h5 class="fw-bold font-heading mb-2">Activate Plan</h5>
                        <p class="text-muted small mb-0">Select an investment tier to automatically start your 24h daily ROI cycle.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <span class="step-number">04</span>
                        <div class="step-icon badge-green">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <h5 class="fw-bold font-heading mb-2">Earn Daily</h5>
                        <p class="text-muted small mb-0">Receive daily payouts up to 300% (3X) cap and withdraw anytime.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Referral & Leadership Salary Section -->
    <section class="py-5 bg-white" id="referrals">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="section-tag">&bull; 10-LEVEL REFERRALS &bull;</span>
                    <h2 class="section-title">20% Direct Reward + 10 Levels Matrix</h2>
                    <p class="text-muted mb-4">
                        Share your referral link to instantly earn 20% on all direct sponsor investments, plus passive multi-tier commissions spanning 10 deep matrix levels.
                    </p>

                    <div class="row g-2 mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded-3 border bg-light">
                                <h4 class="fw-bold text-orange mb-0 font-monospace">20% Instant</h4>
                                <small class="text-muted">Direct Referral Reward</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 border bg-light">
                                <h4 class="fw-bold text-success mb-0 font-monospace">10 Levels</h4>
                                <small class="text-muted">L1: 5%, L2: 4%, L3-4: 3%, L5-6: 2%, L7-10: 1%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Salary Card -->
                <div class="col-lg-6" data-aos="fade-left" id="salary">
                    <div class="p-4 rounded-4 border shadow-sm" style="background: linear-gradient(145deg, #ffffff, #f0fdf4);">
                        <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-3 py-1 mb-3 fw-bold">
                            <i class="bi bi-award-fill me-1"></i> Leadership Career
                        </span>
                        <h3 class="fw-bold font-heading mb-2 text-dark">Monthly Leadership Salaries</h3>
                        <p class="text-muted small mb-4">Earn up to $300/month recurring salary based on active qualifying direct members with min $50 investment.</p>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                    <tr class="text-muted small">
                                        <th>Level</th>
                                        <th>Required Directs</th>
                                        <th class="text-end">Monthly Salary</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salaryLevels as $sl)
                                        <tr>
                                            <td class="fw-bold">{{ $sl->name }}</td>
                                            <td><span class="badge bg-dark rounded-pill">{{ $sl->required_directs }} Directs</span></td>
                                            <td class="text-end fw-bold text-success font-monospace">${{ number_format($sl->monthly_salary, 2) }}/mo</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">L1 ($20) to L5 ($300) tiers active.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5" id="faq">
        <div class="container">
            <div class="text-center max-w-xl mx-auto mb-5" data-aos="fade-up">
                <span class="section-tag">&bull; FREQUENTLY ASKED QUESTIONS &bull;</span>
                <h2 class="section-title">Got Questions? We Have Answers</h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="accordion accordion-flush bg-white rounded-4 border p-3 shadow-sm" id="mainFaqAccordion">
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#f1">
                                    What is the 300% (3X) Multiplier Protocol?
                                </button>
                            </h2>
                            <div id="f1" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
                                <div class="accordion-body text-muted small">
                                    Every active investment plan generates automated daily ROI up to a strict 300% (3X) total earning limit. When your cumulative earnings reach 3X the initial principal, the investment automatically concludes.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#f2">
                                    What cryptocurrencies are accepted?
                                </button>
                            </h2>
                            <div id="f2" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
                                <div class="accordion-body text-muted small">
                                    We primarily accept USDT on the TRON (TRC20) network for zero-volatility deposits, alongside multi-crypto automated processing via NOWPayments.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold py-3" type="button" data-bs-toggle="collapse" data-bs-target="#f3">
                                    How quickly are withdrawals processed?
                                </button>
                            </h2>
                            <div id="f3" class="accordion-collapse collapse" data-bs-parent="#mainFaqAccordion">
                                <div class="accordion-body text-muted small">
                                    Withdrawals are reviewed and processed within standard business hours with a transparent 5% platform fee. The minimum withdrawal threshold is ${{ setting('min_withdrawal', 10) }}.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-fg">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="navbar-brand-icon" style="width: 32px; height: 32px; font-size: 1rem;">
                            <i class="bi bi-layers-fill"></i>
                        </div>
                        <span class="fs-4 fw-bold text-white font-heading">Future<span style="color: var(--fg-orange);">Growth</span></span>
                    </div>
                    <p class="small text-muted mb-3" style="max-width: 400px;">
                        {{ setting('footer_text', 'FutureGrowth is an automated USDT investment ecosystem providing daily passive returns, 10-tier community rewards, and sustainable wealth creation.') }}
                    </p>
                </div>

                <div class="col-6 col-lg-3">
                    <h6 class="text-white fw-bold mb-3 text-uppercase text-xs">Quick Links</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><a href="#plans" class="text-muted text-decoration-none">Investment Plans</a></li>
                        <li class="mb-2"><a href="#referrals" class="text-muted text-decoration-none">10-Level Referral</a></li>
                        <li class="mb-2"><a href="#salary" class="text-muted text-decoration-none">Leadership Salary</a></li>
                        <li class="mb-2"><a href="{{ route('terms') }}" class="text-muted text-decoration-none">Terms of Service</a></li>
                        <li class="mb-2"><a href="{{ route('privacy') }}" class="text-muted text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-4">
                    <h6 class="text-white fw-bold mb-3 text-uppercase text-xs">Community & Support</h6>
                    <p class="small text-muted mb-3">Connect directly with our administrators and global member community:</p>
                    <div class="d-flex gap-2">
                        @if(setting('whatsapp_community_link'))
                            <a href="{{ setting('whatsapp_community_link') }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                                <i class="bi bi-whatsapp me-1"></i> WhatsApp
                            </a>
                        @endif
                        @if(setting('telegram_link'))
                            <a href="{{ setting('telegram_link') }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                <i class="bi bi-telegram me-1"></i> Telegram
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pt-4 border-top border-secondary border-opacity-25 text-center text-muted small">
                &copy; {{ date('Y') }} FutureGrowth.tech. All rights reserved. 300% (3X) Multiplier Protocol.
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS & AOS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 700, once: true });
    </script>
</body>
</html>
