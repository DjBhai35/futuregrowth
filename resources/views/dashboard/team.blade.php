@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-dark font-heading">
            <i class="bi bi-people-fill text-success me-2"></i> Referral Network
        </h2>
        <p class="text-muted small mb-0">Build your team and earn commissions across 10 levels of distribution plus monthly leadership salary.</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('dashboard.salary') }}" class="btn btn-outline-warning rounded-pill px-3 py-1.5 btn-sm fw-bold">
            <i class="bi bi-award-fill me-1"></i> Leadership Salary Portal
        </a>
        <div class="bg-white px-3 py-1.5 border rounded-pill d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-link-45deg text-success fs-5"></i>
            <span class="text-muted text-xs">My Code:</span>
            <strong class="text-dark font-monospace text-xs">{{ Auth::user()->referral_code }}</strong>
            <button class="btn btn-sm btn-fg-primary rounded-pill px-2.5 py-0.5 text-xs ms-1" onclick="navigator.clipboard.writeText('{{ route('register', ['ref' => Auth::user()->referral_code]) }}'); alert('Referral link copied to clipboard!');">Copy Link</button>
        </div>
    </div>
</div>

<!-- Leadership Salary Teaser Banner -->
<div class="fg-card p-3 p-md-4 mb-4 border-warning border-opacity-40 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3" data-aos="fade-up" style="background: linear-gradient(135deg, rgba(249, 115, 22, 0.04) 0%, rgba(22, 163, 74, 0.06) 100%);">
    <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle p-2.5 bg-warning bg-opacity-20 text-warning d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
            <i class="bi bi-award-fill fs-4"></i>
        </div>
        <div>
            <h6 class="fw-bold text-dark mb-0">Earn Up to $300 Monthly Leadership Salary</h6>
            <p class="text-muted small mb-0">Qualify by having active Level 1 direct referrals with minimum $50 active investment. Non-cumulative monthly claims.</p>
        </div>
    </div>
    <a href="{{ route('dashboard.salary') }}" class="btn btn-fg-orange btn-sm fw-bold rounded-pill px-3 py-2 shrink-0">
        Check Qualification <i class="bi bi-arrow-right ms-1"></i>
    </a>
</div>

<!-- Summary Stats Cards Grid -->
<div class="row g-3 mb-4" data-aos="fade-up">
    <!-- Total Team Members -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="fg-card p-3 h-100 d-flex align-items-center gap-3 border-primary border-opacity-30">
            <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                <i class="bi bi-people-fill fs-4"></i>
            </div>
            <div>
                <p class="text-muted text-xs mb-0 text-uppercase fw-bold">Total Team</p>
                <h4 class="fw-bold text-dark font-monospace mb-0">{{ number_format($teamSize) }}</h4>
                <span class="text-muted text-[11px]">All 10 Levels</span>
            </div>
        </div>
    </div>
    
    <!-- Direct Referrals -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="fg-card p-3 h-100 d-flex align-items-center gap-3 border-info border-opacity-30">
            <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                <i class="bi bi-person-fill-check fs-4"></i>
            </div>
            <div>
                <p class="text-muted text-xs mb-0 text-uppercase fw-bold">Direct Referrals</p>
                <h4 class="fw-bold text-dark font-monospace mb-0">{{ number_format($directReferralsCount) }}</h4>
                <span class="text-info text-[11px]">Level 1 Members</span>
            </div>
        </div>
    </div>

    <!-- Team Investment Volume -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="fg-card p-3 h-100 d-flex align-items-center gap-3 border-success border-opacity-30">
            <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                <i class="bi bi-wallet2 fs-4"></i>
            </div>
            <div>
                <p class="text-muted text-xs mb-0 text-uppercase fw-bold">Team Volume</p>
                <h4 class="fw-bold text-success font-monospace mb-0">${{ number_format($teamVolume, 2) }}</h4>
                <span class="text-muted text-[11px]">Active Downline Capital</span>
            </div>
        </div>
    </div>

    <!-- Lifetime Earnings -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="fg-card p-3 h-100 d-flex align-items-center gap-3 border-warning border-opacity-30">
            <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                <i class="bi bi-trophy-fill fs-4"></i>
            </div>
            <div>
                <p class="text-muted text-xs mb-0 text-uppercase fw-bold">Total Earnings</p>
                <h4 class="fw-bold text-warning font-monospace mb-0">${{ number_format($lifetimeEarnings, 2) }}</h4>
                <span class="text-muted text-[11px]">Commissions Disbursed</span>
            </div>
        </div>
    </div>
</div>

<!-- Timeframe Stats Row -->
<div class="row g-3 mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="col-md-4">
        <div class="fg-card p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted text-xs fw-bold text-uppercase">Today's Earnings</span>
            <strong class="text-success fs-5 font-monospace">${{ number_format($todayEarnings, 2) }}</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="fg-card p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted text-xs fw-bold text-uppercase">Weekly Earnings</span>
            <strong class="text-primary fs-5 font-monospace">${{ number_format($weeklyEarnings, 2) }}</strong>
        </div>
    </div>
    <div class="col-md-4">
        <div class="fg-card p-3 d-flex justify-content-between align-items-center">
            <span class="text-muted text-xs fw-bold text-uppercase">Monthly Earnings</span>
            <strong class="text-warning fs-5 font-monospace">${{ number_format($monthlyEarnings, 2) }}</strong>
        </div>
    </div>
</div>

<!-- Level Matrix Breakdown -->
<div class="fg-card p-4 mb-4" data-aos="fade-up" data-aos-delay="150">
    <div class="border-bottom pb-3 mb-3">
        <h5 class="fw-bold text-dark font-heading mb-0">10-Level Downline Performance</h5>
        <p class="text-muted small mb-0">Distribution statistics across all 10 tiers of your matrix downline.</p>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle text-xs">
            <thead class="bg-light text-muted text-uppercase">
                <tr>
                    <th>Level</th>
                    <th class="text-end">Commissions (%)</th>
                    <th class="text-end">Number of Users</th>
                    <th class="text-end">Total Deposits</th>
                    <th class="text-end">Total Investments</th>
                    <th class="text-end">Total Team Business</th>
                    <th class="text-end">Referral Earnings</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-light">
                @foreach($levelsData as $ld)
                <tr>
                    <td>
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-1 fw-bold">Level {{ $ld['level'] }}</span>
                    </td>
                    <td class="text-end fw-bold text-success font-monospace">{{ $ld['percent'] }}%</td>
                    <td class="text-end text-dark font-monospace">{{ number_format($ld['total_users']) }}</td>
                    <td class="text-end text-success fw-bold font-monospace">${{ number_format($ld['total_deposits'], 2) }}</td>
                    <td class="text-end text-dark fw-bold font-monospace">${{ number_format($ld['total_investments'], 2) }}</td>
                    <td class="text-end text-info fw-bold font-monospace">${{ number_format($ld['team_business'], 2) }}</td>
                    <td class="text-end text-warning fw-bold font-monospace">${{ number_format($ld['referral_earnings'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Direct Referral Details -->
<div class="fg-card p-4" data-aos="fade-up" data-aos-delay="200">
    <div class="border-bottom pb-3 mb-3">
        <h5 class="fw-bold text-dark font-heading mb-0">Direct Referrals (Level 1 Details)</h5>
        <p class="text-muted small mb-0">List of partners who registered directly using your personal link.</p>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle text-xs">
            <thead class="bg-light text-muted text-uppercase">
                <tr>
                    <th>User</th>
                    <th>Username</th>
                    <th>Joined Date</th>
                    <th class="text-end">Active Investments</th>
                    <th>Salary Qualifying ($50+)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-light">
                @forelse($referrals as $ref)
                @php
                    $refActiveSum = $ref->investments()->where('status', 'active')->sum('amount');
                    $isQualifiedDirect = $refActiveSum >= 50.00;
                @endphp
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 34px; height: 34px;">
                                {{ strtoupper(substr($ref->name, 0, 1)) }}
                            </div>
                            <span class="fw-bold text-dark">{{ $ref->name }}</span>
                        </div>
                    </td>
                    <td class="text-muted font-monospace">{{ $ref->username }}</td>
                    <td class="text-muted small font-monospace">{{ $ref->created_at->format('M d, Y') }}</td>
                    <td class="fw-bold text-success text-end font-monospace">${{ number_format($refActiveSum, 2) }}</td>
                    <td>
                        @if($isQualifiedDirect)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1 rounded-pill">
                                <i class="bi bi-patch-check-fill me-1"></i> Qualified (≥ $50)
                            </span>
                        @else
                            <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill">
                                <i class="bi bi-hourglass-split me-1"></i> Under $50 (${{ number_format($refActiveSum, 0) }})
                            </span>
                        @endif
                    </td>
                    <td>
                        @if($ref->status == 'active')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-0.5 rounded-pill">Active</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-0.5 rounded-pill">Suspended</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-people display-4 d-block mb-3 opacity-25"></i>
                        No direct referrals found. Share your link to start building your network!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
