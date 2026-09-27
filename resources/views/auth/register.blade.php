@extends('layouts.app')

@section('content')
<style>
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

<div class="row align-items-center justify-content-center py-2" style="min-height: 75vh;">
    <!-- Promotional Left Column (Desktop) -->
    <div class="col-lg-5 d-none d-lg-block" data-aos="fade-right">
        <div class="pe-lg-4">
            <span class="badge bg-warning bg-opacity-15 text-dark border border-warning border-opacity-30 px-3 py-1.5 rounded-pill fw-bold text-xs mb-3">
                <i class="bi bi-star-fill text-warning me-1"></i> Early Adopter Privilege
            </span>
            <h1 class="fw-extrabold mb-3 text-dark font-heading display-6">
                Start Your <span style="color: var(--fg-orange);">Financial Growth</span> Today
            </h1>
            <p class="text-muted fs-6 mb-4">
                Create your verified account in seconds and unlock automated daily ROI, 20% direct referral rewards, and monthly leadership salary.
            </p>
            
            <div class="d-flex flex-column gap-3">
                @if(setting('signup_bonus', 7) > 0)
                <div class="fg-card p-3 promo-card d-flex align-items-center border-warning border-opacity-30">
                    <div class="bg-warning bg-opacity-15 p-3 rounded-circle me-3 text-warning">
                        <i class="bi bi-gift-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Claim Your ${{ setting('signup_bonus', 7) }} Bonus</h6>
                        <p class="text-muted small mb-0">Credited automatically upon verified registration.</p>
                    </div>
                </div>
                @endif
                
                <div class="fg-card p-3 promo-card d-flex align-items-center border-success border-opacity-30">
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3 text-success">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Instant Daily ROI</h6>
                        <p class="text-muted small mb-0">Daily returns credited straight to your ROI wallet every 24 hours.</p>
                    </div>
                </div>
                
                <div class="fg-card p-3 promo-card d-flex align-items-center border-info border-opacity-30">
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle me-3 text-info">
                        <i class="bi bi-diagram-3-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">10-Level Matrix & Leadership Salary</h6>
                        <p class="text-muted small mb-0">20% direct commission + up to $300/month recurring salary.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Registration Form Column -->
    <div class="col-lg-6 col-md-10 col-12" data-aos="fade-left">
        <div class="fg-card p-4 p-md-5 border-emerald-500 border-opacity-30 position-relative shadow-lg">
            <div class="text-center mb-4">
                <div class="d-inline-flex p-3 rounded-circle bg-success bg-opacity-10 text-success mb-2">
                    <i class="bi bi-person-plus-fill fs-2"></i>
                </div>
                <h3 class="fw-bold text-dark font-heading mb-1">Create Account</h3>
                <p class="text-muted small">Join the high-growth USDT investment platform</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger small rounded-3 py-2 px-3 mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Full Name</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-person text-success"></i></span>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="John Doe" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Username</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-at text-success"></i></span>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="johndoe12" required>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Email Address</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-envelope text-success"></i></span>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="name@example.com" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Phone Number</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-telephone text-success"></i></span>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+123456789" required>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Password</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-lock text-success"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Confirm Password</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-shield-check text-success"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted text-xs text-uppercase fw-bold">Referral Code (Optional)</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text"><i class="bi bi-link-45deg text-warning"></i></span>
                        <input type="text" name="referral_code" value="{{ $ref ?? old('referral_code', '') }}" class="form-control fw-bold" {{ isset($ref) ? 'readonly' : '' }} placeholder="Enter sponsor referral code">
                    </div>
                </div>

                @if(setting('enable_recaptcha', false))
                <div class="mb-3 d-flex justify-content-center">
                    <div class="g-recaptcha" data-sitekey="{{ setting('recaptcha_site_key') }}"></div>
                </div>
                @endif

                <button type="submit" class="btn btn-fg-primary w-100 py-2.5 fs-6 fw-bold shadow-md mt-2">
                    <i class="bi bi-check2-circle me-1"></i> Register Free Account
                </button>
                
                <div class="text-center mt-3 pt-2 border-top">
                    <span class="text-muted small">Already registered?</span> 
                    <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none fs-6 ms-1">
                        Sign In Here <i class="bi bi-arrow-right"></i>
                    </a>
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
