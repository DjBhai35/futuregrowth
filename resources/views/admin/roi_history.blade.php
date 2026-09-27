@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-info"><i class="bi bi-graph-up-arrow me-2"></i> Platform ROI Distribution History</h2>
        <p class="text-muted small mb-0">Complete audit trail of all automated and manual daily ROI distributions.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.transactions') }}" class="btn btn-outline-warning btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-journal-text me-1"></i> Global Ledger
        </a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
    </div>
</div>

<!-- ROI KPI Summary -->
<div class="row g-3 mb-4" data-aos="fade-up">
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-3 h-100 border-info border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Total ROI Paid (Lifetime)</span>
            <h3 class="fw-bold text-info mb-0 font-monospace">${{ number_format($totalRoiPaid ?? 0, 2) }}</h3>
            <small class="text-muted text-[10px]">All daily payouts distributed to users</small>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-3 h-100 border-success border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">Distributed Today</span>
            <h3 class="fw-bold text-success mb-0 font-monospace">${{ number_format($todayRoiPaid ?? 0, 2) }}</h3>
            <small class="text-muted text-[10px]">Automated 24h distributions today</small>
        </div>
    </div>
    <div class="col-md-12 col-lg-4">
        <div class="glass-card p-3 h-100 border-warning border-opacity-30">
            <span class="text-muted text-xs d-block mb-1 text-uppercase fw-bold">3X Multiplier Protocol</span>
            <h3 class="fw-bold text-warning mb-0">300% Cap</h3>
            <small class="text-muted text-[10px]">Strict total earnings limit per plan</small>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="glass-card p-4 mb-4" data-aos="fade-up" data-aos-delay="50">
    <form action="{{ route('admin.roi-history') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label text-muted small fw-bold">Search User or Investment</label>
            <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Search by name, email, Investment ID..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted small fw-bold">From Date</label>
            <input type="date" name="date_from" class="form-control bg-dark border-secondary text-white" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label text-muted small fw-bold">To Date</label>
            <input type="date" name="date_to" class="form-control bg-dark border-secondary text-white" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-1 d-flex gap-2">
            <button type="submit" class="btn btn-info fw-bold w-100"><i class="bi bi-filter"></i></button>
            @if(request()->hasAny(['search', 'date_from', 'date_to']))
                <a href="{{ route('admin.roi-history') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                    <th>Tx ID</th>
                    <th>Recipient User</th>
                    <th>ROI Amount</th>
                    <th>Wallet Type</th>
                    <th>Status</th>
                    <th>Description</th>
                    <th class="text-end">Distribution Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roiTransactions as $roi)
                    <tr>
                        <td class="text-muted small font-monospace">#{{ $roi->id }}</td>
                        <td>
                            @if($roi->user)
                                <a href="{{ route('admin.users.show', $roi->user->id) }}" class="text-decoration-none">
                                    <div class="fw-bold text-white">{{ $roi->user->name }}</div>
                                    <small class="text-muted">{{ $roi->user->email }} | {{ $roi->user->username }}</small>
                                </a>
                            @else
                                <span class="text-muted small">System</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-info font-monospace">+${{ number_format($roi->amount, 2) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-dark border border-info text-info rounded-pill px-2">
                                {{ str_replace('_', ' ', $roi->wallet_type) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-20 text-success rounded-pill px-2">Disbursed</span>
                        </td>
                        <td class="small text-muted">
                            {{ $roi->description }}
                        </td>
                        <td class="text-end small text-muted font-monospace">
                            {{ $roi->created_at->format('M d, Y H:i:s') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-graph-up fs-1 d-block mb-2 text-secondary"></i>
                            No ROI distributions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($roiTransactions->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $roiTransactions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
