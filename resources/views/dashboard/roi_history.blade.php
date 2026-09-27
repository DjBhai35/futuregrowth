@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-dark font-heading">
            <i class="bi bi-graph-up text-info me-2"></i> Daily ROI History
        </h2>
        <p class="text-muted small mb-0">Daily returns distributed directly to your ROI wallet from active investment plans.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard.investments') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-box-arrow-in-right me-1"></i> Active Plans
        </a>
        <a href="{{ route('dashboard.history') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-receipt me-1"></i> All Transactions
        </a>
    </div>
</div>

<!-- ROI Metrics Cards -->
<div class="row g-3 mb-4" data-aos="fade-up">
    <div class="col-sm-6 col-lg-4">
        <div class="fg-card p-3 h-100 border-info border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Total ROI Earned</span>
            <h3 class="fw-bold text-info mb-0 font-monospace">${{ number_format($totalRoiEarned, 2) }}</h3>
            <small class="text-muted text-[11px]"><i class="bi bi-wallet2 me-1"></i> Credited to ROI Balance</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="fg-card p-3 h-100 border-success border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Active Investments</span>
            <h3 class="fw-bold text-success mb-0 font-monospace">{{ $activeInvestmentsCount }}</h3>
            <small class="text-muted text-[11px]">Currently generating daily returns</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="fg-card p-3 h-100 border-primary border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Active Capital Volume</span>
            <h3 class="fw-bold text-dark mb-0 font-monospace">${{ number_format($totalInvested, 2) }}</h3>
            <small class="text-muted text-[11px]">300% (3X) Return Multiplier Cap</small>
        </div>
    </div>
</div>

<!-- Date Filter Form -->
<div class="fg-card p-3 mb-4" data-aos="fade-up" data-aos-delay="50">
    <form action="{{ route('dashboard.history.roi') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-4 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm bg-white border">
        </div>
        <div class="col-md-4 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm bg-white border">
        </div>
        <div class="col-md-4 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-fg-primary btn-sm w-100 fw-bold">
                <i class="bi bi-funnel-fill me-1"></i> Filter Records
            </button>
            @if(request()->hasAny(['date_from', 'date_to']))
                <a href="{{ route('dashboard.history.roi') }}" class="btn btn-outline-secondary btn-sm px-3" title="Reset Filters">
                    <i class="bi bi-x-circle"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- ROI Records Table -->
<div class="fg-card p-4" data-aos="fade-up" data-aos-delay="100">
    @if($roiTransactions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-xs">
                <thead class="bg-light text-muted text-uppercase">
                    <tr>
                        <th scope="col">Date & Time</th>
                        <th scope="col">Investment Reference</th>
                        <th scope="col">Plan & Rate</th>
                        <th scope="col">ROI Amount</th>
                        <th scope="col">Wallet Credited</th>
                        <th scope="col">Status</th>
                        <th scope="col">Transaction ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light">
                    @foreach($roiTransactions as $tx)
                        @php
                            $investment = isset($investments[$tx->reference_id]) ? $investments[$tx->reference_id] : null;
                        @endphp
                        <tr>
                            <td class="text-muted font-monospace">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($investment)
                                    <div>
                                        <span class="fw-bold text-dark d-block">Investment #{{ $investment->id }}</span>
                                        <small class="text-muted font-monospace">${{ number_format($investment->amount, 2) }} Principal</small>
                                    </div>
                                @else
                                    <span class="text-dark">{{ $tx->description }}</span>
                                @endif
                            </td>
                            <td>
                                @if($investment && $investment->plan)
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2.5 py-1 rounded-pill">
                                        {{ $investment->plan->name }} ({{ number_format($investment->daily_roi_percent ?? $investment->plan->min_roi, 1) }}%/day)
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">Active Plan</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-info fs-6 font-monospace">+${{ number_format($tx->amount, 2) }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ str_replace('_', ' ', Str::title($tx->wallet_type)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0.5 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> {{ ucfirst($tx->status) }}
                                </span>
                            </td>
                            <td class="font-monospace text-muted">
                                <span class="badge bg-light text-dark border">#{{ $tx->id }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $roiTransactions->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-graph-up fs-1 d-block mb-2 text-secondary opacity-50"></i>
            <h5 class="text-dark fw-bold">No ROI distributions found</h5>
            <p class="small text-muted mb-3">Activate an investment plan to start receiving automated daily return distributions.</p>
            <a href="{{ route('dashboard.investments') }}" class="btn btn-fg-primary btn-sm rounded-pill px-4">
                <i class="bi bi-plus-circle me-1"></i> Browse Investment Plans
            </a>
        </div>
    @endif
</div>
@endsection
