@extends('layouts.app')

@section('content')
<style>
    .salary-hero-card {
        background: linear-gradient(135deg, #062810 0%, #0c3e1c 50%, #062810 100%);
        border: 1px solid rgba(16, 185, 129, 0.3);
        border-radius: 1.5rem;
        box-shadow: 0 10px 25px -5px rgba(6, 40, 16, 0.25);
    }
    .tier-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.85rem;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.025em;
    }
    .tier-active {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.4);
    }
    .tier-locked {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
</style>

<!-- Top Breadcrumb & Title -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-30 px-2.5 py-1 rounded-pill fw-bold">
                <i class="bi bi-award-fill me-1"></i> Leadership Rewards
            </span>
            <span class="text-muted small">Period: <strong class="text-dark">{{ $currentPeriod }}</strong></span>
        </div>
        <h2 class="fw-bold text-dark font-heading mb-0">Monthly Leadership Salary</h2>
        <p class="text-muted small mb-0">Earn guaranteed monthly salary based on your direct team members with qualifying active investments ($50+).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard.team') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
            <i class="bi bi-people me-1"></i> View Team
        </a>
        <a href="{{ route('dashboard.history') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-receipt me-1"></i> All Transactions
        </a>
    </div>
</div>

<!-- Alerts -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 fg-card border-success border-opacity-50" role="alert">
        <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 fg-card border-danger border-opacity-50" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- ==========================================
     1. HERO SALARY STATUS & CLAIM CONSOLE
     ========================================== -->
<div class="salary-hero-card p-4 p-md-5 mb-4 position-relative overflow-hidden text-white" data-aos="fade-up">
    <div class="row align-items-center g-4">
        <!-- Left details -->
        <div class="col-lg-7">
            <div class="d-flex items-center gap-2 mb-2">
                @if($eligibility['qualified_level'])
                    <span class="tier-pill tier-active">
                        <i class="bi bi-star-fill text-warning"></i>
                        {{ $eligibility['qualified_level']->name }} (Level {{ $eligibility['qualified_level']->level_number }})
                    </span>
                @else
                    <span class="tier-pill tier-locked">
                        <i class="bi bi-lock-fill"></i> Ineligible for Salary Tiers
                    </span>
                @endif
                <span class="text-light opacity-75 small">Monthly Entitlement</span>
            </div>

            <h1 class="display-5 fw-extrabold text-white mb-2">
                ${{ number_format($eligibility['salary_amount'], 2) }}
                <span class="fs-6 text-light opacity-75 fw-normal">/ month</span>
            </h1>

            <p class="text-light opacity-90 small mb-3">
                @if($eligibility['qualified_level'])
                    You have qualified for <strong>{{ $eligibility['qualified_level']->name }}</strong> with <strong>{{ $eligibility['qualifying_directs'] }}</strong> active direct members investing at least $50. Salary tiers are non-cumulative.
                @else
                    You currently have <strong>{{ $eligibility['qualifying_directs'] }}</strong> qualifying direct members. You need at least <strong>5</strong> qualifying direct members (with min $50 investment) to unlock Level 1 salary ($20/month).
                @endif
            </p>

            <!-- Progress toward next level -->
            @if($eligibility['next_level'])
                <div class="p-3 rounded-2xl bg-black bg-opacity-30 border border-white border-opacity-10 mb-3">
                    <div class="d-flex justify-content-between text-xs mb-1">
                        <span class="text-light opacity-75">Progress to <strong>{{ $eligibility['next_level']->name }}</strong> (${{ number_format($eligibility['next_level']->monthly_salary, 0) }}/mo)</span>
                        <span class="text-warning fw-bold">{{ $eligibility['qualifying_directs'] }} / {{ $eligibility['next_level']->required_directs }} Directs</span>
                    </div>
                    @php
                        $pct = min(100, round(($eligibility['qualifying_directs'] / max(1, $eligibility['next_level']->required_directs)) * 100));
                    @endphp
                    <div class="progress rounded-pill bg-dark" style="height: 8px;">
                        <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $pct }}%; background: linear-gradient(90deg, #f97316, #10b981);"></div>
                    </div>
                    <small class="text-light opacity-75 text-[11px] mt-1 d-block">
                        Need <strong>{{ $eligibility['needed_for_next_level'] }}</strong> more qualifying direct member(s) with active investment of at least ${{ number_format($eligibility['next_level']->min_investment, 0) }}.
                    </small>
                </div>
            @else
                <div class="p-3 rounded-2xl bg-success bg-opacity-20 border border-success border-opacity-30 mb-3 text-xs text-light d-flex align-items-center gap-2">
                    <i class="bi bi-trophy-fill text-warning fs-5"></i>
                    <span>Congratulations! You have achieved the highest leadership salary tier (Level 5 — $300/month).</span>
                </div>
            @endif

            <div class="d-flex flex-wrap gap-3 text-xs text-light opacity-75">
                <div>Total Directs: <strong class="text-white">{{ $eligibility['total_directs'] }}</strong></div>
                <div>•</div>
                <div>Qualifying Directs: <strong class="text-warning">{{ $eligibility['qualifying_directs'] }}</strong></div>
                <div>•</div>
                <div>Total Claimed: <strong class="text-white">${{ number_format($eligibility['total_claimed'], 2) }}</strong></div>
            </div>
        </div>

        <!-- Right Claim Action Card -->
        <div class="col-lg-5">
            <div class="bg-white text-dark rounded-4 p-4 text-center shadow-lg">
                <div class="mb-3">
                    <span class="text-muted small text-uppercase fw-bold">Period Payout Status</span>
                    <h4 class="text-dark fw-bold mt-1 mb-0">{{ $currentPeriod }}</h4>
                </div>

                @if($eligibility['has_claimed_current_period'])
                    <div class="p-3 rounded-3 bg-light border border-success border-opacity-30 mb-3 text-start">
                        <div class="d-flex items-center gap-2 text-success fw-bold text-xs mb-1">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Period Salary Claimed</span>
                        </div>
                        <p class="text-muted small mb-0">
                            You have already claimed your ${{ number_format($eligibility['existing_claim']->amount, 2) }} salary for {{ $currentPeriod }} on {{ $eligibility['existing_claim']->claimed_at ? $eligibility['existing_claim']->claimed_at->format('M d, Y H:i') : '' }}.
                        </p>
                    </div>
                    <button class="btn btn-secondary w-100 py-2.5 rounded-pill text-sm font-semibold opacity-75 cursor-not-allowed" disabled>
                        <i class="bi bi-check2-all me-1"></i> Already Claimed for {{ $currentPeriod }}
                    </button>
                    <small class="text-muted text-[11px] d-block mt-2">
                        Next claim opens: <strong>{{ \Carbon\Carbon::parse($currentPeriod . '-01')->addMonth()->format('F 01, Y') }}</strong>
                    </small>
                @elseif($eligibility['can_claim'])
                    <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-30 mb-3 text-start">
                        <div class="d-flex items-center gap-2 text-success fw-bold text-xs mb-1">
                            <i class="bi bi-award-fill"></i>
                            <span>You are Eligible to Claim!</span>
                        </div>
                        <p class="text-muted small mb-0">
                            Click below to immediately credit <strong>${{ number_format($eligibility['salary_amount'], 2) }}</strong> to your account salary balance.
                        </p>
                    </div>
                    <form action="{{ route('dashboard.salary.claim') }}" method="POST" onsubmit="this.querySelector('button').disabled = true;">
                        @csrf
                        <button type="submit" class="btn btn-fg-primary w-100 py-2.5 rounded-pill text-sm fw-bold shadow-lg">
                            <i class="bi bi-wallet2 me-1"></i> Claim ${{ number_format($eligibility['salary_amount'], 2) }} Salary Now
                        </button>
                    </form>
                    <small class="text-muted text-[11px] d-block mt-2">
                        Guaranteed monthly distribution. Processed instantly to your wallet.
                    </small>
                @else
                    <div class="p-3 rounded-3 bg-light border mb-3 text-start">
                        <div class="text-muted fw-bold text-xs mb-1">
                            <i class="bi bi-info-circle me-1"></i> Ineligible for Current Period
                        </div>
                        <p class="text-muted small mb-0">
                            Direct members qualify when they maintain an active investment of at least $50. Once 5 members qualify, you will unlock monthly salary.
                        </p>
                    </div>
                    <button class="btn btn-outline-secondary w-100 py-2.5 rounded-pill text-sm font-medium opacity-50 cursor-not-allowed" disabled>
                        Ineligible to Claim
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     2. SALARY TIERS MATRIX
     ========================================== -->
<div class="fg-card p-4 mb-4" data-aos="fade-up" data-aos-delay="50">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold text-dark font-heading mb-1"><i class="bi bi-diagram-3 text-success me-2"></i> Leadership Salary Tiers</h5>
            <p class="text-muted small mb-0">Database-driven compensation schedule. Highest achieved tier is paid monthly.</p>
        </div>
        <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill">
            Non-Cumulative
        </span>
    </div>

    <div class="row g-3">
        @foreach($levels as $lvl)
            @php
                $isCurrentTier = $eligibility['qualified_level'] && $eligibility['qualified_level']->id === $lvl->id;
                $isUnlocked = $eligibility['qualifying_directs'] >= $lvl->required_directs;
            @endphp
            <div class="col-md-6 col-lg">
                <div class="p-3 rounded-3 h-100 border transition {{ $isCurrentTier ? 'bg-success bg-opacity-10 border-success shadow-sm' : ($isUnlocked ? 'bg-light border-secondary border-opacity-30' : 'bg-white border-light opacity-75') }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge {{ $isCurrentTier ? 'bg-success text-white' : ($isUnlocked ? 'bg-secondary text-light' : 'bg-light text-dark border') }} rounded-pill px-2.5 py-1 text-xs fw-bold">
                            {{ $lvl->name }}
                        </span>
                        @if($isCurrentTier)
                            <span class="badge bg-warning text-dark fw-bold text-[10px]">CURRENT</span>
                        @elseif($isUnlocked)
                            <span class="badge bg-success bg-opacity-20 text-success text-[10px]">QUALIFIED</span>
                        @else
                            <i class="bi bi-lock text-muted small"></i>
                        @endif
                    </div>

                    <h4 class="fw-extrabold text-dark mb-1 font-monospace">${{ number_format($lvl->monthly_salary, 0) }} <span class="fs-6 text-muted fw-normal">/mo</span></h4>
                    
                    <div class="space-y-1 text-xs mt-2">
                        <div class="text-muted">Required Directs: <strong class="text-dark">{{ $lvl->required_directs }}</strong></div>
                        <div class="text-muted">Min Investment: <strong class="text-dark">${{ number_format($lvl->min_investment, 0) }}</strong></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- ==========================================
     3. DIRECT DOWNLINE QUALIFICATION BREAKDOWN
     ========================================== -->
<div class="fg-card p-4 mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold text-dark font-heading mb-1"><i class="bi bi-people text-info me-2"></i> Direct Team Qualification Status</h5>
            <p class="text-muted small mb-0">Direct referrals with an active investment of at least $50 count toward your leadership salary.</p>
        </div>
        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 rounded-pill fw-bold">
            {{ count($qualifyingDirects) }} / {{ count($directMembers) }} Qualifying
        </span>
    </div>

    @if(count($directMembers) > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-xs">
                <thead class="bg-light text-muted text-uppercase">
                    <tr>
                        <th scope="col">Direct Member</th>
                        <th scope="col">Registered Email</th>
                        <th scope="col">Active Investment</th>
                        <th scope="col">Qualification Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light">
                    @foreach($directMembers as $member)
                        @php
                            $activeTotal = $member->investments->where('status', 'active')->sum('amount');
                            $qualifies = $activeTotal >= 50.00;
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-bold text-dark">{{ $member->name }}</span>
                                <small class="text-muted d-block font-monospace">({{ $member->username }})</small>
                            </td>
                            <td class="text-muted">{{ $member->email }}</td>
                            <td>
                                <span class="fw-bold text-{{ $qualifies ? 'success' : 'muted' }} fs-6 font-monospace">
                                    ${{ number_format($activeTotal, 2) }}
                                </span>
                            </td>
                            <td>
                                @if($qualifies)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill">
                                        <i class="bi bi-check-circle-fill me-1"></i> Qualified (≥ $50)
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill">
                                        Ineligible (${{ number_format($activeTotal, 2) }} / $50 req)
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-person-plus fs-1 d-block mb-2 text-secondary opacity-50"></i>
            You haven't referred any direct members yet.
            <div class="mt-2">
                <a href="{{ route('dashboard.team') }}" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                    Copy Referral Link
                </a>
            </div>
        </div>
    @endif
</div>

<!-- ==========================================
     4. PAST SALARY CLAIMS HISTORY
     ========================================== -->
<div class="fg-card p-4" data-aos="fade-up" data-aos-delay="150">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold text-dark font-heading mb-1"><i class="bi bi-clock-history text-warning me-2"></i> My Salary Claims History</h5>
            <p class="text-muted small mb-0">Traceable historical payouts credited to your account.</p>
        </div>
    </div>

    @if($claims->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-xs">
                <thead class="bg-light text-muted text-uppercase">
                    <tr>
                        <th scope="col">Period</th>
                        <th scope="col">Level</th>
                        <th scope="col">Qualifying Directs</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Claimed At</th>
                        <th scope="col">Status</th>
                        <th scope="col">Transaction</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light">
                    @foreach($claims as $claim)
                        <tr>
                            <td><span class="badge bg-light text-dark border font-monospace">{{ $claim->claim_period }}</span></td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                    Level {{ $claim->level_number }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $claim->qualifying_directs }} / {{ $claim->required_directs }} required</td>
                            <td><strong class="text-success fs-6 font-monospace">${{ number_format($claim->amount, 2) }}</strong></td>
                            <td class="text-muted">{{ $claim->claimed_at ? $claim->claimed_at->format('M d, Y H:i') : '' }}</td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Completed
                                </span>
                            </td>
                            <td>
                                @if($claim->transaction_id)
                                    <span class="badge bg-light text-dark border font-monospace">#{{ $claim->transaction_id }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3 d-flex justify-content-end">
            {{ $claims->links() }}
        </div>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
            No previous salary claims recorded yet.
        </div>
    @endif
</div>
@endsection
