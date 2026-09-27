@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-journal-text me-2"></i> Global Financial Ledger</h2>
        <p class="text-muted small mb-0">Platform-wide audit trail of all financial movements across all wallets and users.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.roi-history') }}" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-graph-up me-1"></i> ROI History
        </a>
        <a href="{{ route('admin.salary.claims') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-award me-1"></i> Salary History
        </a>
        <a href="{{ route('admin.reports') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-file-earmark-bar-graph me-1"></i> Financial Reports
        </a>
    </div>
</div>

<!-- Ledger Summary Cards -->
<div class="row g-3 mb-4" data-aos="fade-up">
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-success border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Approved Deposits</span>
            <h4 class="fw-bold text-success mb-0 font-monospace">${{ number_format($stats['total_deposits'] ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Net platform deposits</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-danger border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Disbursed Withdrawals</span>
            <h4 class="fw-bold text-danger mb-0 font-monospace">${{ number_format($stats['total_withdrawals'] ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Paid out to clients</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-info border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Total ROI Paid</span>
            <h4 class="fw-bold text-info mb-0 font-monospace">${{ number_format($stats['total_roi'] ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Automated daily earnings</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-primary border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Commissions Paid</span>
            <h4 class="fw-bold text-primary mb-0 font-monospace">${{ number_format($stats['total_commissions'] ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Direct & 10-level matrix</small>
        </div>
    </div>
    <div class="col-sm-6 col-lg">
        <div class="glass-card p-3 h-100 border-warning border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Salaries Paid</span>
            <h4 class="fw-bold text-warning mb-0 font-monospace">${{ number_format($stats['total_salaries'] ?? 0, 2) }}</h4>
            <small class="text-muted text-[10px]">Monthly leadership claims</small>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="glass-card p-4 mb-4" data-aos="fade-up" data-aos-delay="50">
    <form action="{{ route('admin.transactions') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label text-muted small fw-bold">Search</label>
            <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="User name, email, description..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">Type</label>
            <select name="type" class="form-select bg-dark border-secondary text-white">
                <option value="all">All Types</option>
                <option value="deposit" {{ request('type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                <option value="withdrawal" {{ request('type') === 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                <option value="roi" {{ request('type') === 'roi' ? 'selected' : '' }}>Daily ROI</option>
                <option value="commission" {{ request('type') === 'commission' ? 'selected' : '' }}>Referral Commission</option>
                <option value="salary" {{ request('type') === 'salary' ? 'selected' : '' }}>Leadership Salary</option>
                <option value="bonus" {{ request('type') === 'bonus' ? 'selected' : '' }}>Bonus</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">Wallet</label>
            <select name="wallet" class="form-select bg-dark border-secondary text-white">
                <option value="all">All Wallets</option>
                <option value="deposit_balance" {{ request('wallet') === 'deposit_balance' ? 'selected' : '' }}>Deposit Wallet</option>
                <option value="roi_balance" {{ request('wallet') === 'roi_balance' ? 'selected' : '' }}>ROI Wallet</option>
                <option value="referral_balance" {{ request('wallet') === 'referral_balance' ? 'selected' : '' }}>Referral Wallet</option>
                <option value="salary_balance" {{ request('wallet') === 'salary_balance' ? 'selected' : '' }}>Salary Wallet</option>
                <option value="bonus_balance" {{ request('wallet') === 'bonus_balance' ? 'selected' : '' }}>Bonus Wallet</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">From Date</label>
            <input type="date" name="date_from" class="form-control bg-dark border-secondary text-white" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">To Date</label>
            <input type="date" name="date_to" class="form-control bg-dark border-secondary text-white" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-1 d-flex gap-2">
            <button type="submit" class="btn btn-warning fw-bold w-100"><i class="bi bi-filter"></i></button>
            @if(request()->hasAny(['search', 'type', 'wallet', 'status', 'date_from', 'date_to']))
                <a href="{{ route('admin.transactions') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<!-- Table -->
<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Wallet Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th class="text-end">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $tx)
                    <tr>
                        <td class="text-muted small font-monospace">#{{ $tx->id }}</td>
                        <td>
                            @if($tx->user)
                                <a href="{{ route('admin.users.show', $tx->user->id) }}" class="text-decoration-none">
                                    <div class="fw-bold text-white">{{ $tx->user->name }}</div>
                                    <small class="text-muted">{{ $tx->user->email }}</small>
                                </a>
                            @else
                                <span class="text-muted small">System / Anonymous</span>
                            @endif
                        </td>
                        <td>
                            @if($tx->type === 'deposit')
                                <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="bi bi-arrow-down-circle me-1"></i> Deposit
                                </span>
                            @elseif($tx->type === 'withdrawal')
                                <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="bi bi-arrow-up-circle me-1"></i> Withdrawal
                                </span>
                            @elseif($tx->type === 'roi')
                                <span class="badge bg-info bg-opacity-20 text-info border border-info border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="bi bi-graph-up me-1"></i> Daily ROI
                                </span>
                            @elseif($tx->type === 'commission')
                                <span class="badge bg-primary bg-opacity-20 text-primary border border-primary border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="bi bi-people me-1"></i> Commission
                                </span>
                            @elseif($tx->type === 'salary')
                                <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 rounded-pill px-3 py-1">
                                    <i class="bi bi-award me-1"></i> Salary
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-1">{{ ucfirst($tx->type) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-dark border border-secondary text-muted font-monospace small">
                                {{ str_replace('_', ' ', $tx->wallet_type ?? 'general') }}
                            </span>
                        </td>
                        <td>
                            @if(in_array($tx->type, ['deposit', 'roi', 'commission', 'salary', 'bonus']))
                                <span class="fw-bold text-success font-monospace">+${{ number_format($tx->amount, 2) }}</span>
                            @else
                                <span class="fw-bold text-danger font-monospace">-${{ number_format($tx->amount, 2) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($tx->status === 'completed' || $tx->status === 'approved')
                                <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2">Completed</span>
                            @elseif($tx->status === 'pending')
                                <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill px-2">Pending</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-2">{{ ucfirst($tx->status) }}</span>
                            @endif
                        </td>
                        <td class="small text-muted" style="max-width: 250px;">
                            {{ $tx->description }}
                        </td>
                        <td class="text-end small text-muted font-monospace">
                            {{ $tx->created_at->format('M d, Y H:i:s') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            No transactions found matching the filter criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
