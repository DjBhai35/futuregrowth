@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-dark font-heading">
            <i class="bi bi-rocket-takeoff text-success me-2"></i> Investment Packages
        </h2>
        <p class="text-muted small mb-0">Select an automated crypto yield package to start receiving daily returns with a guaranteed 300% (3X) cap.</p>
    </div>
    <div class="d-flex align-items-center gap-3 bg-white px-4 py-2 rounded-pill border shadow-sm">
        <div class="rounded-circle bg-success bg-opacity-10 p-2 text-success">
            <i class="bi bi-wallet2 fs-5"></i>
        </div>
        <div>
            <span class="text-muted text-[11px] d-block text-uppercase fw-bold">Available Balance</span>
            <h4 class="text-success fw-bold font-monospace mb-0">${{ number_format($totalBalance, 2) }}</h4>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 fg-card border-success border-opacity-50 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 fg-card border-danger border-opacity-50 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-4 justify-content-center">
    @foreach($plans as $index => $plan)
    @php
        $colors = [
            'Starter' => ['icon' => 'bi-lightning-charge-fill', 'gradient' => 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)', 'bg' => 'rgba(2, 132, 199, 0.1)', 'color' => '#0284c7'],
            'Growth' => ['icon' => 'bi-rocket-takeoff-fill', 'gradient' => 'linear-gradient(135deg, #16a34a 0%, #15803d 100%)', 'bg' => 'rgba(22, 163, 74, 0.1)', 'color' => '#16a34a'],
            'Professional' => ['icon' => 'bi-gem', 'gradient' => 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)', 'bg' => 'rgba(249, 115, 22, 0.1)', 'color' => '#f97316'],
            'Elite' => ['icon' => 'bi-shield-shaded', 'gradient' => 'linear-gradient(135deg, #062810 0%, #0d471d 100%)', 'bg' => 'rgba(6, 40, 16, 0.1)', 'color' => '#062810']
        ];
        $config = $colors[$plan->name] ?? ['icon' => 'bi-box-fill', 'gradient' => 'linear-gradient(135deg, #16a34a 0%, #0d471d 100%)', 'bg' => 'rgba(22, 163, 74, 0.1)', 'color' => '#16a34a'];
        $isPopular = ($plan->name === 'Growth' || $plan->name === 'Professional');
    @endphp
    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ 100 * ($index + 1) }}">
        <div class="fg-card h-100 d-flex flex-column position-relative overflow-hidden shadow-sm hover-scale {{ $isPopular ? 'border-success border-2' : '' }}">
            @if($isPopular)
                <div class="position-absolute top-0 end-0 text-white fw-bold px-3 py-1" style="border-bottom-left-radius: 1rem; font-size: 0.68rem; background: var(--fg-orange); letter-spacing: 1px;">POPULAR</div>
            @endif
            
            <div class="p-4 text-center border-bottom position-relative">
                <div class="p-3 rounded-circle d-inline-flex mb-3" style="background: {{ $config['bg'] }}; color: {{ $config['color'] }};">
                    <i class="bi {{ $config['icon'] }} fs-3"></i>
                </div>
                <h4 class="fw-bold text-dark font-heading mb-1">{{ $plan->name }}</h4>
                <span class="badge bg-light text-muted border px-2.5 py-1 text-xs">300% (3X) Cap Multiplier</span>
            </div>
            
            <div class="p-4 flex-grow-1 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Min Deposit</span>
                        <strong class="text-dark font-monospace">${{ number_format($plan->min_amount, 0) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted small">
                        <span>Max Deposit</span>
                        <strong class="text-dark font-monospace">${{ number_format($plan->max_amount, 0) }}</strong>
                    </div>
                    
                    <div class="p-3 rounded-3 text-center small mb-4 border" style="background: {{ $config['bg'] }}; border-color: rgba(22, 163, 74, 0.2) !important;">
                        <span class="text-muted d-block text-xs text-uppercase fw-bold">Daily Return</span>
                        <strong class="fs-5 text-dark font-monospace">{{ $plan->min_roi }}% - {{ $plan->max_roi }}%</strong>
                        <span class="text-muted d-block text-[11px] mt-0.5">Credited to ROI balance</span>
                    </div>
                </div>

                <form action="{{ route('dashboard.investments.store') }}" method="POST" class="mt-auto">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <div class="input-group mb-3 shadow-sm">
                        <span class="input-group-text bg-light text-muted fw-bold">$</span>
                        <input type="number" step="0.01" min="{{ $plan->min_amount }}" max="{{ $plan->max_amount }}" name="amount" class="form-control" placeholder="Amount..." required>
                    </div>
                    <button type="submit" class="btn w-100 fw-bold py-2.5 rounded-pill text-white shadow-sm" style="background: {{ $config['gradient'] }};">
                        <i class="bi bi-lightning-charge me-1"></i> Activate Plan
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

<style>
    .hover-scale { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
    .hover-scale:hover { transform: translateY(-5px) !important; box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.1) !important; }
</style>
@endsection
