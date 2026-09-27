@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1 text-white"><i class="bi bi-people-fill text-primary me-2"></i> Referral Commission History</h2>
        <p class="text-muted small mb-0">Traceable ledger of all direct and 10-level matrix commissions credited to your account.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('dashboard.team') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-diagram-3 me-1"></i> My Team Structure
        </a>
        <a href="{{ route('dashboard.history') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-receipt me-1"></i> All Transactions
        </a>
    </div>
</div>

<!-- Commission Summary Metrics -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="glass-card p-3 h-100 border-primary border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Total Commission Earned</span>
            <h3 class="fw-bold text-white mb-0 font-monospace">${{ number_format($totalCommissions, 2) }}</h3>
            <small class="text-primary text-[11px]"><i class="bi bi-wallet2 me-1"></i> Credited to Referral Wallet</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="glass-card p-3 h-100 border-success border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Direct Sponsor Rewards (20%)</span>
            <h3 class="fw-bold text-success mb-0 font-monospace">${{ number_format($directCommissions, 2) }}</h3>
            <small class="text-muted text-[11px]">Instant 20% on direct deposits</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="glass-card p-3 h-100 border-info border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Multi-Level Matrix (L1–L10)</span>
            <h3 class="fw-bold text-info mb-0 font-monospace">${{ number_format($matrixCommissions, 2) }}</h3>
            <small class="text-muted text-[11px]">Generational team volume</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="glass-card p-3 h-100 border-secondary border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Total Direct Referrals</span>
            <h3 class="fw-bold text-warning mb-0 font-monospace">{{ $totalDirects }}</h3>
            <small class="text-muted text-[11px]">Registered downline members</small>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="glass-card p-3 mb-4">
    <form action="{{ route('dashboard.history.referrals') }}" method="GET" class="row g-2 align-items-end">
        <div class="col-md-3 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">Commission Level</label>
            <select name="level" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="all" {{ request('level') == 'all' || !request('level') ? 'selected' : '' }}>All Levels</option>
                <option value="direct" {{ request('level') == 'direct' ? 'selected' : '' }}>Direct Sponsor (20%)</option>
                @for($l = 1; $l <= 10; $l++)
                    <option value="{{ $l }}" {{ request('level') == (string)$l ? 'selected' : '' }}>Level {{ $l }} Commission</option>
                @endfor
            </select>
        </div>
        <div class="col-md-3 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm bg-dark text-white border-secondary">
        </div>
        <div class="col-md-3 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm bg-dark text-white border-secondary">
        </div>
        <div class="col-md-3 col-6 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                <i class="bi bi-funnel-fill me-1"></i> Filter
            </button>
            <a href="{{ route('dashboard.history.referrals') }}" class="btn btn-outline-secondary btn-sm px-3" title="Reset Filters">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>
</div>

<!-- Commission Records Table -->
<div class="glass-card p-4">
    @if($commissions->count() > 0)
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 text-xs">
                <thead class="text-muted text-uppercase">
                    <tr>
                        <th scope="col">Date & Time</th>
                        <th scope="col">Downline Member</th>
                        <th scope="col">Commission Level</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Wallet Credited</th>
                        <th scope="col">Status</th>
                        <th scope="col">Transaction ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary divide-opacity-25">
                    @foreach($commissions as $tx)
                        @php
                            $downlineUser = isset($downlineUsers[$tx->reference_id]) ? $downlineUsers[$tx->reference_id] : null;
                            $isDirect = str_contains($tx->description, 'Direct Reward');
                        @endphp
                        <tr>
                            <td class="text-muted font-monospace">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($downlineUser)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-secondary bg-opacity-30 text-white d-flex align-items-center justify-content-center fw-bold" style="width: 26px; height: 26px; font-size: 10px;">
                                            {{ strtoupper(substr($downlineUser->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="fw-bold text-white d-block">{{ $downlineUser->name }}</span>
                                            <small class="text-muted font-monospace">@ {{ $downlineUser->username }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-light">{{ $tx->description }}</span>
                                @endif
                            </td>
                            <td>
                                @if($isDirect)
                                    <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-2.5 py-1 rounded-pill">
                                        <i class="bi bi-star-fill me-1 text-warning"></i> Direct Reward (20%)
                                    </span>
                                @else
                                    <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-30 px-2.5 py-1 rounded-pill">
                                        {{ explode(' from', $tx->description)[0] }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success fs-6 font-monospace">+${{ number_format($tx->amount, 2) }}</strong>
                            </td>
                            <td class="text-muted">
                                <span class="badge bg-dark border border-secondary text-slate-300">
                                    {{ str_replace('_', ' ', Str::title($tx->wallet_type)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-20 text-success px-2 py-0.5 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> {{ ucfirst($tx->status) }}
                                </span>
                            </td>
                            <td class="font-monospace text-muted">
                                <span class="badge bg-dark border border-secondary text-info">#{{ $tx->id }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $commissions->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
            <h5 class="text-white fw-bold">No referral commissions found</h5>
            <p class="small text-muted mb-3">When members in your downline make active investments, your earned commissions will record here in real time.</p>
            <a href="{{ route('dashboard.team') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                <i class="bi bi-share-fill me-1"></i> Get Your Referral Link
            </a>
        </div>
    @endif
</div>
@endsection
