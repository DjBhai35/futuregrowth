@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <a href="{{ route('admin.salary') }}" class="text-success small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Salary Management
            </a>
        </div>
        <h2 class="fw-bold text-white mb-0">Salary Claims Ledger</h2>
        <p class="text-muted small mb-0">Historical records of all monthly leadership salary claims and wallet distributions.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.salary') }}" class="btn btn-outline-success btn-sm rounded-pill">
            <i class="bi bi-sliders me-1"></i> Salary Levels
        </a>
    </div>
</div>

<!-- Filters -->
<div class="glass-card p-3 mb-4">
    <form method="GET" action="{{ route('admin.salary.claims') }}" class="row g-2 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control form-control-sm bg-black text-white border-secondary rounded-pill px-3" placeholder="Search user..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="period" class="form-select form-select-sm bg-black text-white border-secondary rounded-pill">
                <option value="">All Periods</option>
                @foreach($periods as $p)
                    <option value="{{ $p }}" {{ request('period') == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm bg-black text-white border-secondary rounded-pill">
                <option value="">All Statuses</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100">Filter</button>
            @if(request()->anyFilled(['search', 'period', 'status']))
                <a href="{{ route('admin.salary.claims') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Clear</a>
            @endif
        </div>
    </form>
</div>

<!-- Claims Table -->
<div class="glass-card p-4">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th scope="col">Claim ID</th>
                    <th scope="col">User</th>
                    <th scope="col">Tier</th>
                    <th scope="col">Qualifying Directs</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Period</th>
                    <th scope="col">Claimed At</th>
                    <th scope="col">Status</th>
                    <th scope="col">Transaction</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $claim)
                <tr>
                    <td><code>#{{ $claim->id }}</code></td>
                    <td>
                        <a href="{{ route('admin.users.show', $claim->user_id) }}" class="text-white text-decoration-none fw-semibold">
                            {{ $claim->user->name ?? 'User #' . $claim->user_id }}
                        </a>
                        <small class="text-muted d-block">{{ $claim->user->email ?? '' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30">
                            {{ $claim->salaryLevel->name ?? 'Level ' . $claim->level_number }}
                        </span>
                    </td>
                    <td>
                        <span>{{ $claim->qualifying_directs }}</span>
                        <small class="text-muted">/ {{ $claim->required_directs }} required</small>
                    </td>
                    <td><strong class="text-success fs-6">${{ number_format($claim->amount, 2) }}</strong></td>
                    <td><code>{{ $claim->claim_period }}</code></td>
                    <td class="text-muted small">{{ $claim->claimed_at ? $claim->claimed_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                    <td>
                        @if($claim->status === 'completed')
                            <span class="badge bg-success bg-opacity-20 text-success px-2 py-1 rounded-pill">
                                <i class="bi bi-check-circle-fill me-1"></i> Completed
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-20 text-warning px-2 py-1 rounded-pill">
                                {{ ucfirst($claim->status) }}
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($claim->transaction_id)
                            <span class="badge bg-dark border border-secondary text-info">TX #{{ $claim->transaction_id }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                        No salary claims found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        {{ $claims->links() }}
    </div>
</div>
@endsection
