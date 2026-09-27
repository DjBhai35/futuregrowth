@extends('layouts.app')

@section('content')
<style>
    /* Promo Banner */
    .promo-banner {
        overflow: hidden;
        white-space: nowrap;
        background: #ffffff;
        border: 1px solid rgba(22, 163, 74, 0.2);
        padding: 10px 0;
        border-radius: 9999px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .marquee-content {
        display: inline-block;
        animation: marquee 22s linear infinite;
    }
    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    .marquee-item {
        display: inline-block;
        margin-right: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        color: #334155;
    }
    .promo-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .promo-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.08);
    }
</style>

<!-- Promotional Marquee -->
<div class="promo-banner mb-4" data-aos="fade-down">
    <div class="marquee-content">
        <span class="marquee-item"><i class="bi bi-gift-fill text-warning me-1"></i> {{ setting('promo_banner_1', 'Free $' . setting('signup_bonus', 7) . ' Signup Bonus Available!') }}</span>
        <span class="marquee-item"><i class="bi bi-rocket-takeoff-fill text-primary me-1"></i> {{ setting('promo_banner_2', 'Build Your Team & Earn up to 10 Levels of Rewards!') }}</span>
        <span class="marquee-item"><i class="bi bi-graph-up-arrow text-success me-1"></i> {{ setting('promo_banner_3', '3X Return on all Investment Plans!') }}</span>
        <span class="marquee-item"><i class="bi bi-whatsapp text-success me-1"></i> {{ setting('promo_banner_4', 'Join our WhatsApp Community!') }}</span>
    </div>
</div>

<div class="row align-items-center justify-content-center py-2" style="min-height: 72vh;">
    <!-- Promotional Left Column (Desktop) -->
    <div class="col-lg-6 d-none d-lg-block" data-aos="fade-right">
        <div class="pe-lg-4">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 rounded-pill fw-bold text-xs mb-3">
                <i class="bi bi-shield-check me-1"></i> Certified Smart Investment Engine
            </span>
            <h1 class="fw-extrabold mb-3 text-dark font-heading display-6">
                The Next Generation of <span style="color: var(--fg-orange);">Crypto Investments</span>
            </h1>
            <p class="text-muted fs-6 mb-4">
                Join thousands of verified members earning automated daily returns transparently. Guaranteed 300% multiplier cap and instant wallet distributions.
            </p>
            
            <div class="d-flex flex-column gap-3">
                <div class="fg-card p-3 promo-card d-flex align-items-center border-success border-opacity-30">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3 text-success">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Automated Daily ROI</h6>
                        <p class="text-muted small mb-0">Daily returns credited straight to your ROI wallet every 24 hours.</p>
                    </div>
                </div>
                
                <div class="fg-card p-3 promo-card d-flex align-items-center border-info border-opacity-30">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3 text-info">
                        <i class="bi bi-diagram-3-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">10-Level Matrix & 20% Direct Reward</h6>
                        <p class="text-muted small mb-0">Earn 20% direct sponsor bonus plus 10 levels of multi-tier commission.</p>
                    </div>
                </div>
                
                <div class="fg-card p-3 promo-card d-flex align-items-center border-warning border-opacity-30">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3 text-warning">
                        <i class="bi bi-award-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Monthly Leadership Salary</h6>
                        <p class="text-muted small mb-0">Earn up to $300/month by mentoring active qualifying direct members.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Login Form Column -->
    <div class="col-lg-5 col-md-8 col-11" data-aos="fade-left">
        <div class="fg-card p-4 p-md-5 border-emerald-500 border-opacity-30 position-relative shadow-lg">
            <div class="text-center mb-4">
                <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-10 text-success mb-2">
                    <i class="bi bi-shield-lock-fill fs-2"></i>
                </div>
                <h3 class="fw-bold text-dark font-heading mb-1">Welcome Back</h3>
                <p class="text-muted small">Login to access your FutureGrowth portal</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success small rounded-3 py-2 px-3 mb-3">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger small rounded-3 py-2 px-3 mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted text-xs text-uppercase fw-bold">Email Address</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text"><i class="bi bi-envelope text-success"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="name@example.com" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Password</label>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none text-success fw-bold">Forgot Password?</a>
                    </div>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text"><i class="bi bi-lock text-success"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                
                <div class="mb-4 form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                    <label class="form-check-label text-muted small" for="rememberMe">Remember this browser</label>
                </div>

                @if(setting('enable_recaptcha', false))
                <div class="mb-4 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site_key') }}"></div>
                </div>
                @endif

                <button type="submit" class="btn btn-fg-primary w-100 py-2.5 fs-6 fw-bold shadow-md">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Secure Login
                </button>
                
                <div class="text-center mt-4 pt-3 border-top">
                    <span class="text-muted small">Don't have an account?</span><br>
                    <a href="{{ route('register') }}" class="fw-bold text-success text-decoration-none fs-6 mt-1 d-inline-block">
                        Create Free Account <i class="bi bi-arrow-right"></i>
                    </a>
                    @if(setting('signup_bonus', 7) > 0)
                        <div class="text-warning small fw-bold mt-1">
                            <i class="bi bi-gift-fill me-1"></i> Claim your ${{ setting('signup_bonus', 7) }} Signup Bonus!
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@if(setting('enable_recaptcha', false))
@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endif
