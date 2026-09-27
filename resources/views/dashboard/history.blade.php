@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1 text-white"><i class="bi bi-journal-text text-primary me-2"></i> Transaction & Financial Ledger</h2>
        <p class="text-muted small mb-0">Traceable, authenticated record of all deposits, withdrawals, daily ROI, referral commissions, and leadership salary.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('dashboard.history.referrals') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-people me-1"></i> Referral History
        </a>
        <a href="{{ route('dashboard.history.roi') }}" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-graph-up me-1"></i> ROI History
        </a>
        <a href="{{ route('dashboard.salary') }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-award me-1"></i> Salary History
        </a>
    </div>
</div>

<!-- Ledger Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-success border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Approved Deposits</span>
            <h4 class="fw-bold text-success mb-0 font-monospace">${{ number_format($totalDeposited ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Total credited to deposit wallet</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-danger border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Paid Withdrawals</span>
            <h4 class="fw-bold text-danger mb-0 font-monospace">${{ number_format($totalWithdrawn ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Successfully disbursed</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-info border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Daily ROI Earned</span>
            <h4 class="fw-bold text-info mb-0 font-monospace">${{ number_format($totalRoi ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">From active investment plans</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-primary border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Referral Commissions</span>
            <h4 class="fw-bold text-primary mb-0 font-monospace">${{ number_format($totalCommissions ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Direct & 10-level matrix</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-warning border-opacity-30">
            <span class="text-muted text-xs d-block mb-1">Leadership Salary</span>
            <h4 class="fw-bold text-warning mb-0 font-monospace">${{ number_format($totalSalary ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Monthly claimed bonuses</small>
        </div>
    </div>
</div>

<!-- Category Tabs & Server-Side Filters -->
<div class="glass-card p-3 mb-4">
    <!-- Quick Type Pills -->
    <div class="d-flex flex-wrap gap-2 pb-3 mb-3 border-b border-secondary border-opacity-25">
        <a href="{{ route('dashboard.history') }}" class="btn btn-sm rounded-pill px-3 {{ !request('type') || request('type') == 'all' ? 'btn-primary' : 'btn-dark border-secondary text-muted' }}">
            All Types
        </a>
        <a href="{{ route('dashboard.history', ['type' => 'deposit']) }}" class="btn btn-sm rounded-pill px-3 {{ request('type') == 'deposit' ? 'btn-success' : 'btn-dark border-secondary text-muted' }}">
            <i class="bi bi-arrow-down-circle me-1"></i> Deposits
        </a>
        <a href="{{ route('dashboard.history', ['type' => 'withdrawal']) }}" class="btn btn-sm rounded-pill px-3 {{ request('type') == 'withdrawal' ? 'btn-danger' : 'btn-dark border-secondary text-muted' }}">
            <i class="bi bi-arrow-up-circle me-1"></i> Withdrawals
        </a>
        <a href="{{ route('dashboard.history', ['type' => 'roi']) }}" class="btn btn-sm rounded-pill px-3 {{ request('type') == 'roi' ? 'btn-info' : 'btn-dark border-secondary text-muted' }}">
            <i class="bi bi-graph-up me-1"></i> Daily ROI
        </a>
        <a href="{{ route('dashboard.history', ['type' => 'commission']) }}" class="btn btn-sm rounded-pill px-3 {{ request('type') == 'commission' ? 'btn-primary' : 'btn-dark border-secondary text-muted' }}">
            <i class="bi bi-people me-1"></i> Commissions
        </a>
        <a href="{{ route('dashboard.history', ['type' => 'salary']) }}" class="btn btn-sm rounded-pill px-3 {{ request('type') == 'salary' ? 'btn-warning text-dark fw-bold' : 'btn-dark border-secondary text-muted' }}">
            <i class="bi bi-award-fill me-1"></i> Leadership Salary
        </a>
        <a href="{{ route('dashboard.history', ['type' => 'bonus']) }}" class="btn btn-sm rounded-pill px-3 {{ request('type') == 'bonus' ? 'btn-secondary text-white' : 'btn-dark border-secondary text-muted' }}">
            <i class="bi bi-gift me-1"></i> Bonuses
        </a>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('dashboard.history') }}" method="GET" class="row g-2 align-items-end">
        <input type="hidden" name="type" value="{{ request('type', 'all') }}">
        <div class="col-md-3 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">Target Wallet</label>
            <select name="wallet" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="all" {{ request('wallet') == 'all' || !request('wallet') ? 'selected' : '' }}>All Wallets</option>
                <option value="deposit_balance" {{ request('wallet') == 'deposit_balance' ? 'selected' : '' }}>Deposit Wallet</option>
                <option value="roi_balance" {{ request('wallet') == 'roi_balance' ? 'selected' : '' }}>ROI Wallet</option>
                <option value="referral_balance" {{ request('wallet') == 'referral_balance' ? 'selected' : '' }}>Referral Wallet</option>
                <option value="salary_balance" {{ request('wallet') == 'salary_balance' ? 'selected' : '' }}>Salary Wallet</option>
                <option value="bonus_balance" {{ request('wallet') == 'bonus_balance' ? 'selected' : '' }}>Bonus Wallet</option>
            </select>
        </div>
        <div class="col-md-3 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">Status</label>
            <select name="status" class="form-select form-select-sm bg-dark text-white border-secondary">
                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed / Approved</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-2 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control form-control-sm bg-dark text-white border-secondary">
        </div>
        <div class="col-md-2 col-6">
            <label class="form-label text-muted text-xs fw-bold mb-1">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control form-control-sm bg-dark text-white border-secondary">
        </div>
        <div class="col-md-2 col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold">
                <i class="bi bi-funnel-fill me-1"></i> Apply
            </button>
            <a href="{{ route('dashboard.history') }}" class="btn btn-outline-secondary btn-sm px-3" title="Clear Filters">
                <i class="bi bi-x-circle"></i>
            </a>
        </div>
    </form>
</div>

<!-- Ledger Table -->
<div class="glass-card p-4">
    @if($transactions->count() > 0)
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0 text-xs">
                <thead class="text-muted text-uppercase">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Type</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Wallet</th>
                        <th scope="col">Description</th>
                        <th scope="col">Reference</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date & Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-secondary divide-opacity-25">
                    @foreach($transactions as $tx)
                    <tr>
                        <td class="text-muted font-monospace">#{{ $tx->id }}</td>
                        <td>
                            @if($tx->type == 'deposit')
                                <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-arrow-down-circle me-1"></i> Deposit
                                </span>
                            @elseif($tx->type == 'withdrawal')
                                <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-30 px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-arrow-up-circle me-1"></i> Withdrawal
                                </span>
                            @elseif($tx->type == 'roi')
                                <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-30 px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-graph-up me-1"></i> Daily ROI
                                </span>
                            @elseif($tx->type == 'commission')
                                <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-30 px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-people-fill me-1"></i> Commission
                                </span>
                            @elseif($tx->type == 'salary')
                                <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-40 px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-award-fill me-1 text-warning"></i> Leadership Salary
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-20 text-light border border-secondary border-opacity-30 px-2.5 py-1 rounded-pill">
                                    <i class="bi bi-gift-fill me-1"></i> Bonus
                                </span>
                            @endif
                        </td>
                        <td>
                            @if(in_array($tx->type, ['deposit', 'roi', 'commission', 'salary', 'bonus']))
                                <strong class="text-success fs-6 font-monospace">+${{ number_format($tx->amount, 2) }}</strong>
                            @else
                                <strong class="text-danger fs-6 font-monospace">-${{ number_format($tx->amount, 2) }}</strong>
                            @endif
                        </td>
                        <td class="text-muted font-monospace">
                            <span class="badge bg-dark border border-secondary text-slate-300">
                                {{ str_replace('_', ' ', Str::title($tx->wallet_type)) }}
                            </span>
                        </td>
                        <td class="text-light text-wrap" style="max-width: 320px;">
                            {{ $tx->description ?: '—' }}
                        </td>
                        <td class="text-muted font-monospace">
                            @if($tx->reference_id)
                                <span class="badge bg-dark border border-secondary text-info">#{{ $tx->reference_id }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($tx->status == 'completed' || $tx->status == 'approved')
                                <span class="badge bg-success bg-opacity-20 text-success px-2 py-0.5 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Completed
                                </span>
                            @elseif($tx->status == 'pending')
                                <span class="badge bg-warning bg-opacity-20 text-warning px-2 py-0.5 rounded-pill">
                                    <i class="bi bi-clock-fill me-1"></i> Pending
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-20 text-danger px-2 py-0.5 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> Rejected
                                </span>
                            @endif
                        </td>
                        <td class="text-muted font-monospace">{{ $tx->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-receipt fs-1 d-block mb-2 text-secondary"></i>
            <h5 class="text-white fw-bold">No transactions found</h5>
            <p class="small text-muted mb-3">No ledger records match the selected filter criteria.</p>
            <a href="{{ route('dashboard.history') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
            </a>
        </div>
    @endif
</div>
@endsection
