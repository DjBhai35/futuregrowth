@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-warning"><i class="bi bi-arrow-down-circle-fill me-2"></i> Deposits Center</h2>
        <p class="text-muted small">Verify TXIDs and approve or reject client USDT deposits.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports') }}?type=deposits" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-bar-graph"></i> Export Deposits</a>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<div class="glass-card p-4 mb-4" data-aos="fade-up">
    <form action="{{ route('admin.deposits') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label text-muted small fw-bold">Search Deposits</label>
            <input type="text" name="search" class="form-control bg-dark border-secondary text-white" placeholder="Search by TXID, Amount, User..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">Network</label>
            <select name="network" class="form-select bg-dark border-secondary text-white">
                <option value="">All Networks</option>
                <option value="trc20" {{ request('network') === 'trc20' ? 'selected' : '' }}>TRC20</option>
                <option value="bep20" {{ request('network') === 'bep20' ? 'selected' : '' }}>BEP20</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label text-muted small fw-bold">Status Filter</label>
            <select name="status" class="form-select bg-dark border-secondary text-white">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-warning fw-bold flex-grow-1">Search & Filter</button>
            @if(request('search') || request('status') || request('network'))
                <a href="{{ route('admin.deposits') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </form>
</div>

<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Network</th>
                    <th>TXID</th>
                    <th>Proof</th>
                    <th>Status</th>
                    <th>Requested Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $dep)
                    <tr>
                        <td>
                            @if($dep->user)
                                <h6 class="fw-bold text-white mb-0">{{ $dep->user->name }}</h6>
                                <small class="text-muted d-block">{{ $dep->user->email }}</small>
                                <small class="text-muted d-block">ID: {{ $dep->user->id }}</small>
                            @else
                                <span class="text-danger small">Deleted User</span>
                            @endif
                        </td>
                        <td class="fw-bold text-success">${{ number_format($dep->amount, 2) }}</td>
                        <td>
                            @if($dep->network === 'trc20')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-cpu me-1"></i>TRC20
                                </span>
                            @elseif($dep->network === 'bep20')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1">
                                    <i class="bi bi-shield-fill-check me-1"></i>BEP20
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2" style="max-width: 240px;">
                                <code class="text-info text-truncate" style="font-size: 0.82rem; max-width:180px;">{{ $dep->txid }}</code>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1"
                                    onclick="navigator.clipboard.writeText('{{ $dep->txid }}')" title="Copy TXID">
                                    <i class="bi bi-copy" style="font-size:0.75rem;"></i>
                                </button>
                                @if($dep->network === 'trc20')
                                <a href="https://tronscan.org/#/transaction/{{ $dep->txid }}" target="_blank"
                                    class="btn btn-sm btn-outline-info py-0 px-1" title="Verify on TronScan">
                                    <i class="bi bi-box-arrow-up-right" style="font-size:0.75rem;"></i>
                                </a>
                                @elseif($dep->network === 'bep20')
                                <a href="https://bscscan.com/tx/{{ $dep->txid }}" target="_blank"
                                    class="btn btn-sm btn-outline-warning py-0 px-1" title="Verify on BSCScan">
                                    <i class="bi bi-box-arrow-up-right" style="font-size:0.75rem;"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($dep->proof_path)
                                <a href="{{ Storage::url($dep->proof_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-info py-0 px-2" title="View Proof">
                                    <i class="bi bi-image me-1"></i>View
                                </a>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>
                            @if($dep->status === 'approved')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i> Approved</span>
                            @elseif($dep->status === 'rejected')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i class="bi bi-x-circle-fill me-1"></i> Rejected</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1"><i class="bi bi-clock-fill me-1"></i> Pending</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $dep->created_at->format('M d, Y H:i') }}</td>
                        <td class="text-end">
                            @if($dep->status === 'pending')
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="{{ route('admin.deposits.approve', $dep->id) }}" method="POST"
                                        onsubmit="return confirm('Approve ${{ number_format($dep->amount, 2) }} deposit for {{ $dep->user?->name ?? 'this user' }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success fw-bold px-3">Approve</button>
                                    </form>
                                    <form action="{{ route('admin.deposits.reject', $dep->id) }}" method="POST"
                                        onsubmit="return confirm('Reject this deposit request?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger fw-bold px-3">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted small">Processed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-2 mb-2 d-block opacity-50"></i>
                            No deposit transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $deposits->links() }}</div>
</div>
@endsection
