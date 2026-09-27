@extends('layouts.app')

@section('content')
<style>
    .salary-badge {
        font-size: 0.75rem;
        padding: 0.35rem 0.65rem;
        border-radius: 9999px;
        font-weight: 600;
        letter-spacing: 0.025em;
    }
    .badge-eligible {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .badge-claimed {
        background: rgba(59, 130, 246, 0.15);
        color: #60a5fa;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    .badge-not-eligible {
        background: rgba(148, 163, 184, 0.12);
        color: #94a3b8;
        border: 1px solid rgba(148, 163, 184, 0.2);
    }
    .metric-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .tier-card {
        transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .tier-card:hover {
        transform: translateY(-3px);
        border-color: rgba(16, 185, 129, 0.4) !important;
    }
</style>

<!-- Header & Breadcrumbs -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-2 py-1 rounded">
                <i class="bi bi-award-fill me-1"></i> Leadership System
            </span>
            <span class="text-muted small">Period: <strong class="text-white">{{ $currentPeriod }}</strong></span>
        </div>
        <h2 class="fw-bold text-white mb-0">Salary Management Foundation</h2>
        <p class="text-muted small mb-0">Manage leadership salary tiers, monitor direct-member qualifications, and review claims.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.salary.claims') }}" class="btn btn-outline-light btn-sm px-3 rounded-pill">
            <i class="bi bi-clock-history me-1"></i> Claims History
        </a>
        <button type="button" class="btn btn-success btn-sm px-3 rounded-pill fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#createTierModal">
            <i class="bi bi-plus-circle me-1"></i> Add Salary Level
        </button>
    </div>
</div>

<!-- Alert notifications -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 glass-card border-success border-opacity-50" role="alert">
        <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 glass-card border-danger border-opacity-50" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- ==========================================
     1. TOP SUMMARY METRICS
     ========================================== -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="glass-card p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">Total Paid (All Time)</span>
                <h3 class="fw-bold text-white mb-0 mt-1">${{ number_format($metrics['total_salary_paid'], 2) }}</h3>
                <small class="text-success"><i class="bi bi-check-all"></i> Verified Ledger</small>
            </div>
            <div class="metric-icon-box bg-success bg-opacity-10 text-success">
                <i class="bi bi-cash-stack fs-4"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="glass-card p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">Current Month ({{ $currentPeriod }})</span>
                <h3 class="fw-bold text-success mb-0 mt-1">${{ number_format($metrics['current_month_paid'], 2) }}</h3>
                <small class="text-muted">{{ $metrics['current_month_claimants'] }} Leaders Paid</small>
            </div>
            <div class="metric-icon-box bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-calendar2-check fs-4"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="glass-card p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">Active Claimants</span>
                <h3 class="fw-bold text-white mb-0 mt-1">{{ number_format($metrics['current_month_claimants']) }}</h3>
                <small class="text-info"><i class="bi bi-person-check"></i> Monthly claims</small>
            </div>
            <div class="metric-icon-box bg-info bg-opacity-10 text-info">
                <i class="bi bi-people-fill fs-4"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="glass-card p-3 d-flex align-items-center justify-content-between">
            <div>
                <span class="text-muted small fw-semibold text-uppercase">Active Salary Tiers</span>
                <h3 class="fw-bold text-white mb-0 mt-1">{{ $metrics['active_levels_count'] }} <span class="text-muted fs-6 fw-normal">/ {{ count($levels) }}</span></h3>
                <small class="text-primary"><i class="bi bi-sliders"></i> Database-driven</small>
            </div>
            <div class="metric-icon-box bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-diagram-3-fill fs-4"></i>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     2. SALARY LEVELS CONFIGURATION
     ========================================== -->
<div class="glass-card p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold text-white mb-1"><i class="bi bi-sliders text-success me-2"></i> Salary Levels Configuration</h5>
            <p class="text-muted small mb-0">Levels are non-cumulative. Users receive only the highest active tier they qualify for.</p>
        </div>
        <span class="badge bg-dark border border-secondary text-muted px-3 py-2 rounded-pill">
            Rule: Directs require min ${{ number_format($levels->first()->min_investment ?? 50, 0) }} active investment
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th scope="col">Tier</th>
                    <th scope="col">Required Directs</th>
                    <th scope="col">Min Direct Investment</th>
                    <th scope="col">Monthly Salary</th>
                    <th scope="col">Status</th>
                    <th scope="col">Description</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($levels as $lvl)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 rounded-circle p-2" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                L{{ $lvl->level_number }}
                            </span>
                            <div>
                                <span class="fw-bold text-white">{{ $lvl->name }}</span>
                                <small class="text-muted d-block">Level {{ $lvl->level_number }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="fw-semibold text-white fs-6">{{ $lvl->required_directs }}</span>
                        <small class="text-muted">directs</small>
                    </td>
                    <td>
                        <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-30">
                            ${{ number_format($lvl->min_investment, 2) }}
                        </span>
                    </td>
                    <td>
                        <span class="fw-bold text-success fs-6">${{ number_format($lvl->monthly_salary, 2) }}</span>
                        <small class="text-muted">/mo</small>
                    </td>
                    <td>
                        @if($lvl->is_active)
                            <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-2 py-1 rounded-pill">
                                <i class="bi bi-check-circle me-1"></i> Active
                            </span>
                        @else
                            <span class="badge bg-danger bg-opacity-20 text-danger border border-danger border-opacity-30 px-2 py-1 rounded-pill">
                                <i class="bi bi-slash-circle me-1"></i> Inactive
                            </span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $lvl->description ?: 'N/A' }}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editTierModal{{ $lvl->id }}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <form action="{{ route('admin.salary.levels.toggle', $lvl->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-{{ $lvl->is_active ? 'secondary' : 'success' }}" title="{{ $lvl->is_active ? 'Disable' : 'Enable' }}">
                                    <i class="bi bi-power"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editTierModal{{ $lvl->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-dark text-white border-secondary">
                                    <form action="{{ route('admin.salary.levels.update', $lvl->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header border-secondary">
                                            <h5 class="modal-title fw-bold">Edit Salary Level {{ $lvl->level_number }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label small text-muted">Tier Name</label>
                                                <input type="text" name="name" class="form-control bg-black text-white border-secondary" value="{{ $lvl->name }}" required>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label small text-muted">Required Directs</label>
                                                    <input type="number" name="required_directs" class="form-control bg-black text-white border-secondary" value="{{ $lvl->required_directs }}" min="1" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small text-muted">Min Investment ($)</label>
                                                    <input type="number" step="0.01" name="min_investment" class="form-control bg-black text-white border-secondary" value="{{ $lvl->min_investment }}" min="0" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small text-muted">Monthly Salary Amount ($)</label>
                                                <input type="number" step="0.01" name="monthly_salary" class="form-control bg-black text-white border-secondary" value="{{ $lvl->monthly_salary }}" min="0" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small text-muted">Description / Notes</label>
                                                <textarea name="description" class="form-control bg-black text-white border-secondary" rows="2">{{ $lvl->description }}</textarea>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="activeSwitch{{ $lvl->id }}" {{ $lvl->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="activeSwitch{{ $lvl->id }}">Tier Active & Selectable</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-secondary">
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success btn-sm fw-bold">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No salary levels defined yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ==========================================
     3. USER SALARY QUALIFICATION INSPECTOR
     ========================================== -->
<div class="glass-card p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
        <div>
            <h5 class="fw-bold text-white mb-1"><i class="bi bi-people text-info me-2"></i> User Leadership & Salary Status</h5>
            <p class="text-muted small mb-0">Real-time server-side qualification calculations based on direct referral investment requirements.</p>
        </div>
        <form method="GET" action="{{ route('admin.salary') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm bg-black text-white border-secondary rounded-pill px-3" placeholder="Search by name, email..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Filter</button>
            @if(request('search'))
                <a href="{{ route('admin.salary') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th scope="col">User</th>
                    <th scope="col">Direct Members</th>
                    <th scope="col">Qualifying Directs</th>
                    <th scope="col">Qualified Tier</th>
                    <th scope="col">Monthly Salary</th>
                    <th scope="col">Period Status ({{ $currentPeriod }})</th>
                    <th scope="col">Total Claimed</th>
                    <th scope="col" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $eligibility = $usersEligibility[$user->id] ?? [
                        'total_directs' => 0,
                        'qualifying_directs' => 0,
                        'qualified_level' => null,
                        'salary_amount' => 0,
                        'is_eligible' => false,
                        'can_claim' => false,
                        'has_claimed_current_period' => false,
                        'total_claimed' => 0,
                    ];
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-white text-decoration-none fw-bold d-flex align-items-center gap-2">
                            <div class="bg-primary bg-opacity-20 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <span>{{ $user->name }}</span>
                                <small class="text-muted d-block">{{ $user->email }} ({{ $user->username }})</small>
                            </div>
                        </a>
                    </td>
                    <td>
                        <span class="fw-semibold text-white">{{ $eligibility['total_directs'] }}</span>
                        <small class="text-muted">directs</small>
                    </td>
                    <td>
                        <span class="badge bg-dark border border-secondary text-info fw-semibold px-2 py-1">
                            {{ $eligibility['qualifying_directs'] }} active (≥ $50)
                        </span>
                    </td>
                    <td>
                        @if($eligibility['qualified_level'])
                            <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30 px-2 py-1 rounded-pill fw-bold">
                                {{ $eligibility['qualified_level']->name }} (Tier {{ $eligibility['qualified_level']->level_number }})
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-muted border border-secondary border-opacity-25 px-2 py-1 rounded-pill">
                                None
                            </span>
                            @if(isset($eligibility['next_level']) && $eligibility['next_level'])
                                <small class="text-muted d-block mt-1">Need {{ $eligibility['needed_for_next_level'] }} more for {{ $eligibility['next_level']->name }}</small>
                            @endif
                        @endif
                    </td>
                    <td>
                        <span class="fw-bold text-{{ $eligibility['salary_amount'] > 0 ? 'success' : 'muted' }}">
                            ${{ number_format($eligibility['salary_amount'], 2) }}
                        </span>
                    </td>
                    <td>
                        @if($eligibility['has_claimed_current_period'])
                            <span class="salary-badge badge-claimed">
                                <i class="bi bi-check-all me-1"></i> Claimed ({{ $currentPeriod }})
                            </span>
                        @elseif($eligibility['can_claim'])
                            <span class="salary-badge badge-eligible">
                                <i class="bi bi-star-fill me-1"></i> Eligible to Claim
                            </span>
                        @else
                            <span class="salary-badge badge-not-eligible">
                                Not Eligible
                            </span>
                        @endif
                    </td>
                    <td>
                        <span class="text-muted small">${{ number_format($eligibility['total_claimed'], 2) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            @if($eligibility['can_claim'])
                                <form action="{{ route('admin.salary.claim-for-user', $user->id) }}" method="POST" onsubmit="return confirm('Process ${{ number_format($eligibility['salary_amount'], 2) }} salary claim for {{ $user->name }} for period {{ $currentPeriod }}?');">
                                    @csrf
                                    <input type="hidden" name="period" value="{{ $currentPeriod }}">
                                    <button type="submit" class="btn btn-success btn-sm fw-semibold">
                                        <i class="bi bi-cash-coin me-1"></i> Process Claim
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>
                                    {{ $eligibility['has_claimed_current_period'] ? 'Claimed' : 'Ineligible' }}
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        {{ $users->links() }}
    </div>
</div>

<!-- ==========================================
     4. RECENT SALARY CLAIMS LEDGER
     ========================================== -->
<div class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold text-white mb-1"><i class="bi bi-clock-history text-warning me-2"></i> Recent Salary Claims</h5>
            <p class="text-muted small mb-0">Traceable financial records and audit log.</p>
        </div>
        <a href="{{ route('admin.salary.claims') }}" class="btn btn-sm btn-outline-warning rounded-pill">
            View All Claims <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle mb-0">
            <thead class="text-muted small text-uppercase">
                <tr>
                    <th scope="col">User</th>
                    <th scope="col">Level</th>
                    <th scope="col">Qualifying Directs</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Period</th>
                    <th scope="col">Claimed At</th>
                    <th scope="col">Status</th>
                    <th scope="col">Transaction</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentClaims as $claim)
                <tr>
                    <td>
                        <span class="fw-bold text-white">{{ $claim->user->name ?? 'User #' . $claim->user_id }}</span>
                        <small class="text-muted d-block">{{ $claim->user->email ?? '' }}</small>
                    </td>
                    <td>
                        <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-30">
                            Level {{ $claim->level_number }}
                        </span>
                    </td>
                    <td>{{ $claim->qualifying_directs }} / {{ $claim->required_directs }} req</td>
                    <td><strong class="text-success">${{ number_format($claim->amount, 2) }}</strong></td>
                    <td><code>{{ $claim->claim_period }}</code></td>
                    <td class="text-muted small">{{ $claim->claimed_at->format('M d, Y H:i') }}</td>
                    <td>
                        <span class="badge bg-success bg-opacity-20 text-success px-2 py-1 rounded-pill">
                            <i class="bi bi-check-circle-fill me-1"></i> Completed
                        </span>
                    </td>
                    <td>
                        @if($claim->transaction_id)
                            <span class="badge bg-dark border border-secondary text-muted">#{{ $claim->transaction_id }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No salary claims recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Tier Modal -->
<div class="modal fade" id="createTierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary">
            <form action="{{ route('admin.salary.levels.store') }}" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-success me-2"></i> Create New Salary Level</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small text-muted">Level Number</label>
                            <input type="number" name="level_number" class="form-control bg-black text-white border-secondary" placeholder="e.g. 6" min="1" required>
                        </div>
                        <div class="col-8">
                            <label class="form-label small text-muted">Level Name</label>
                            <input type="text" name="name" class="form-control bg-black text-white border-secondary" placeholder="e.g. Diamond Leader" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-muted">Required Directs</label>
                            <input type="number" name="required_directs" class="form-control bg-black text-white border-secondary" placeholder="e.g. 200" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted">Min Investment ($)</label>
                            <input type="number" step="0.01" name="min_investment" class="form-control bg-black text-white border-secondary" value="50.00" min="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Monthly Salary ($)</label>
                        <input type="number" step="0.01" name="monthly_salary" class="form-control bg-black text-white border-secondary" placeholder="e.g. 500.00" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Description</label>
                        <textarea name="description" class="form-control bg-black text-white border-secondary" rows="2" placeholder="Optional level description"></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="newTierActive" checked>
                        <label class="form-check-label small" for="newTierActive">Active immediately</label>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm fw-bold">Create Level</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
