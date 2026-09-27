@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-dark font-heading">
            <i class="bi bi-people-fill text-primary me-2"></i> Referral Commission History
        </h2>
        <p class="text-muted small mb-0">Traceable ledger of all direct sponsor rewards and 10-level matrix commissions.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard.team') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-diagram-3 me-1"></i> Team Structure
        </a>
        <a href="{{ route('dashboard.history') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-receipt me-1"></i> All Transactions
        </a>
    </div>
</div>

<!-- Commission Summary Metrics -->
<div class="row g-3 mb-4" data-aos="fade-up">
    <div class="col-sm-6 col-lg-3">
        <div class="fg-card p-3 h-100 border-primary border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Total Commission Earned</span>
            <h3 class="fw-bold text-dark mb-0 font-monospace">${{ number_format($totalCommissions ?? 0, 2) }}</h3>
            <small class="text-primary text-[11px]"><i class="bi bi-wallet2 me-1"></i> Credited to Referral Wallet</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="fg-card p-3 h-100 border-success border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Direct Sponsor Rewards (20%)</span>
            <h3 class="fw-bold text-success mb-0 font-monospace">${{ number_format($directCommissions ?? 0, 2) }}</h3>
            <small class="text-muted text-[11px]">Instant 20% on direct deposits</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="fg-card p-3 h-100 border-info border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Multi-Level Matrix (L1–L10)</span>
            <h3 class="fw-bold text-info mb-0 font-monospace">${{ number_format($matrixCommissions ?? 0, 2) }}</h3>
            <small class="text-muted text-[11px]">Generational team volume</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="fg-card p-3 h-100 border-warning border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Total Direct Referrals</span>
            <h3 class="fw-bold text-warning mb-0 font-monospace">{{ $totalDirects ?? 0 }}</h3>
            <small class="text-muted text-[11px]">Registered downline members</small>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="fg-card p-3 mb-4" data-aos="fade-up" data-aos-delay="50">
    <form action="{{ route('dashboard.history.referrals') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-3 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">Commission Level</label>
            <select name="level" class="form-select form-select-sm bg-white border">
                <option value="all" {{ request('level') == 'all' || !request('level') ? 'selected' : '' }}>All Levels</option>
                <option value="direct" {{ request('level') == 'direct' ? 'selected' : '' }}>Direct Sponsor (20%)</option>
                @for($l = 1; $l <= 10; $l++)
                    <option value="{{ $l }}" {{ request('level') == (string)$l ? 'selected' : '' }}>Level {{ $l }} Commission</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm bg-white border">
        </div>
        <div class="col-md-3 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm bg-white border">
        </div>
        <div class="col-md-3 col-6 d-flex gap-2">
            <button type="submit" class="btn btn-fg-primary btn-sm w-100 fw-bold">
                <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            @if(request()->hasAny(['level', 'date_from', 'date_to']))
                <a href="{{ route('dashboard.history.referrals') }}" class="btn btn-outline-secondary btn-sm px-3" title="Clear Filters">
                    <i class="bi bi-x-circle"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Commissions Table -->
<div class="fg-card p-4" data-aos="fade-up" data-aos-delay="100">
    @if($commissions->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-xs">
                <thead class="bg-light text-muted text-uppercase">
                    <tr>
                        <th>Tx ID</th>
                        <th>Type / Level</th>
                        <th>Amount</th>
                        <th>Generated By (Downline)</th>
                        <th>Description</th>
                        <th class="text-end">Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commissions as $comm)
                    @php
                        $downline = $downlineUsers[$comm->reference_id] ?? null;
                    @endphp
                    <tr>
                        <td class="text-muted font-monospace">#{{ $comm->id }}</td>
                        <td>
                            @if(str_contains($comm->description, 'Direct Reward'))
                                <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-3 py-1">
                                    <i class="bi bi-star-fill me-1"></i> Direct 20%
                                </span>
                            @else
                                <span class="badge bg-primary bg-opacity-15 text-primary rounded-pill px-3 py-1">
                                    <i class="bi bi-diagram-3-fill me-1"></i> Matrix
                                </span>
                            @endif
                        </td>
                        <td>
                            <strong class="text-success fs-6 font-monospace">+${{ number_format($comm->amount, 2) }}</strong>
                        </td>
                        <td>
                            @if($downline)
                                <div class="fw-bold text-dark">{{ $downline->name }}</div>
                                <small class="text-muted font-monospace">{{ $downline->username ?? $downline->email }}</small>
                            @else
                                <span class="text-muted font-monospace">User #{{ $comm->reference_id ?: '—' }}</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $comm->description }}</td>
                        <td class="text-end text-muted font-monospace">{{ $comm->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($commissions->hasPages())
            <div class="d-flex justify-content-center pt-4">
                {{ $commissions->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="bi bi-people fs-1 text-muted d-block mb-3"></i>
            <h5 class="fw-bold text-dark">No Referral Commissions Yet</h5>
            <p class="text-muted small">Share your link to start earning direct 20% and 10-level matrix rewards.</p>
        </div>
    @endif
</div>
@endsection
