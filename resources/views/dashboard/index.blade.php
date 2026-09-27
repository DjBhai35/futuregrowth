@extends('layouts.app')

@section('content')
<style>
    /* Metric Cards (Reference Theme Style) */
    .metric-card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid var(--fg-border);
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        height: 100%;
    }

    .metric-card:hover {
        transform: translateY(-4px);
        border-color: rgba(22, 163, 74, 0.25);
        box-shadow: 0 12px 24px -4px rgba(22, 163, 74, 0.08);
    }

    .metric-label {
        font-size: 0.8rem;
        color: var(--fg-text-muted);
        font-weight: 600;
        margin-bottom: 0.35rem;
    }

    .metric-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--fg-forest);
        font-family: 'Outfit', sans-serif;
        line-height: 1.1;
        margin: 0;
    }

    .metric-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .box-green {
        background: #f0fdf4;
        color: var(--fg-emerald);
        border: 1px solid rgba(22, 163, 74, 0.2);
    }

    .box-orange {
        background: #fff7ed;
        color: var(--fg-orange);
        border: 1px solid rgba(249, 115, 22, 0.2);
    }

    /* Circular Progress Meter Card (Reference Theme Center) */
    .gauge-card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid var(--fg-border);
        padding: 1.75rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        height: 100%;
    }

    .gauge-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .running-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.85rem;
        border-radius: 9999px;
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid rgba(22, 163, 74, 0.25);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
    }

    .running-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #16a34a;
        box-shadow: 0 0 8px #16a34a;
        animation: pulseDot 2s infinite;
    }

    /* Dual Arc Circular Gauge (SVG) */
    .gauge-svg-wrap {
        position: relative;
        width: 170px;
        height: 170px;
        margin: 0 auto;
    }

    .gauge-center-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2.25rem;
        color: var(--fg-forest);
    }

    /* Countdown Display */
    .countdown-timer-box {
        background: #f8fafc;
        border: 1px solid var(--fg-border);
        border-radius: 1rem;
        padding: 0.75rem 1.25rem;
        text-align: center;
        margin-bottom: 1rem;
    }

    .countdown-digits {
        font-size: 1.65rem;
        font-weight: 900;
        font-family: 'Outfit', monospace;
        color: var(--fg-forest);
        letter-spacing: 0.05em;
    }

    /* Quick Action Cards (Reference Theme Bottom Row) */
    .action-card {
        background: #ffffff;
        border-radius: 1.25rem;
        border: 1px solid var(--fg-border);
        padding: 1.25rem;
        text-decoration: none;
        color: inherit;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    }

    .action-card:hover {
        transform: translateY(-4px);
        border-color: rgba(22, 163, 74, 0.3);
        box-shadow: 0 10px 24px -2px rgba(22, 163, 74, 0.1);
        color: inherit;
    }

    .action-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .action-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--fg-forest);
        margin-bottom: 0.15rem;
    }

    .action-desc {
        font-size: 0.775rem;
        color: var(--fg-text-muted);
        margin: 0;
    }
</style>

<!-- Top Row: 4 Metric Cards (Matching Reference Theme) -->
<div class="row g-3 mb-4" data-aos="fade-down">
    <!-- Card 1: Total Balance -->
    <div class="col-6 col-lg-3">
        <div class="metric-card">
            <div>
                <div class="metric-label">Total Balance</div>
                <div class="metric-value font-monospace">${{ number_format($totalBalance ?? 0, 2) }}</div>
            </div>
            <div class="metric-icon-box box-green">
                <i class="bi bi-wallet2"></i>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Invested -->
    <div class="col-6 col-lg-3">
        <div class="metric-card">
            <div>
                <div class="metric-label">Total Invested</div>
                <div class="metric-value font-monospace text-orange" style="color: var(--fg-orange);">${{ number_format($activeInvestmentsSum ?? 0, 2) }}</div>
            </div>
            <div class="metric-icon-box box-orange">
                <i class="bi bi-graph-up-arrow"></i>
            </div>
        </div>
    </div>

    <!-- Card 3: Total Earnings -->
    <div class="col-6 col-lg-3">
        <div class="metric-card">
            <div>
                <div class="metric-label">Total Earnings</div>
                <div class="metric-value font-monospace text-success">${{ number_format($totalEarnings ?? 0, 2) }}</div>
            </div>
            <div class="metric-icon-box box-green">
                <i class="bi bi-cash-coin"></i>
            </div>
        </div>
    </div>

    <!-- Card 4: ROI Earned -->
    <div class="col-6 col-lg-3">
        <div class="metric-card">
            <div>
                <div class="metric-label">Daily ROI Earned</div>
                <div class="metric-value font-monospace">${{ number_format($roiEarned ?? 0, 2) }}</div>
            </div>
            <div class="metric-icon-box box-orange">
                <i class="bi bi-currency-dollar"></i>
            </div>
        </div>
    </div>
</div>

<!-- Center Section: Active Investment Meter + Side Info & Chart (Matching Reference Theme) -->
<div class="row g-4 mb-4">
    <!-- Main Center Card: Active Investment Gauge -->
    @php
        $latestActiveInv = $activeInvestmentsList->first();
        $hasActive = $latestActiveInv !== null;
        $maxReturn = $hasActive ? ($latestActiveInv->amount * setting('investment_return_multiplier', 3)) : 0;
        $earnedSoFar = $hasActive ? $latestActiveInv->total_earned : 0;
        $progressPct = ($hasActive && $maxReturn > 0) ? min(100, round(($earnedSoFar / $maxReturn) * 100)) : 0;
    @endphp

    <div class="col-lg-6" data-aos="fade-right">
        <div class="gauge-card">
            <div class="gauge-header">
                <h5 class="fw-bold font-heading mb-0 text-dark">
                    <i class="bi bi-cpu-fill text-success me-2"></i> Active Investment
                </h5>
                @if($hasActive)
                    <span class="running-badge">
                        <span class="running-dot"></span> RUNNING
                    </span>
                @else
                    <span class="badge bg-secondary rounded-pill px-3 py-1">INACTIVE</span>
                @endif
            </div>

            <div class="row align-items-center g-4 my-2">
                <!-- Dual Arc Circular Meter (Green & Orange) -->
                <div class="col-sm-5 text-center">
                    <div class="gauge-svg-wrap">
                        <svg width="170" height="170" viewBox="0 0 170 170">
                            <!-- Background Track -->
                            <circle cx="85" cy="85" r="70" fill="none" stroke="#f1f5f9" stroke-width="12" stroke-dasharray="330 110" stroke-linecap="round" transform="rotate(135 85 85)" />
                            <!-- Green Progress Arc -->
                            <circle cx="85" cy="85" r="70" fill="none" stroke="url(#greenGrad)" stroke-width="12" 
                                    stroke-dasharray="330 110" 
                                    stroke-dashoffset="{{ 330 - (330 * ($progressPct / 100)) }}" 
                                    stroke-linecap="round" transform="rotate(135 85 85)" style="transition: stroke-dashoffset 1s ease;" />
                            <!-- Orange Decorative Arc -->
                            <circle cx="85" cy="85" r="54" fill="none" stroke="#f97316" stroke-width="4" stroke-dasharray="120 180" stroke-linecap="round" transform="rotate(45 85 85)" opacity="0.8" />
                            
                            <defs>
                                <linearGradient id="greenGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#16a34a" />
                                    <stop offset="100%" stop-color="#22c55e" />
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="gauge-center-icon">
                            <i class="bi {{ $hasActive ? 'bi-rocket-takeoff text-success' : 'bi-pause-circle text-muted' }}"></i>
                        </div>
                    </div>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-1 mt-2 small fw-bold">
                        {{ $progressPct }}% of 3X Multiplier Cap
                    </span>
                </div>

                <!-- Next ROI Countdown & Investment Details -->
                <div class="col-sm-7">
                    <div class="countdown-timer-box">
                        <small class="text-muted text-uppercase fw-bold text-xs d-block mb-1">Next Daily ROI Payout In</small>
                        <div class="countdown-digits" id="roiCountdown">18 : 42 : 31</div>
                        <div class="d-flex justify-content-between text-[10px] text-muted text-uppercase fw-bold px-2">
                            <span>Hours</span>
                            <span>Minutes</span>
                            <span>Seconds</span>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-muted">Plan Name:</span>
                            <span class="fw-bold text-dark">{{ $latestActiveInv ? ($latestActiveInv->plan->name ?? 'Standard') : 'No Active Plan' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 small">
                            <span class="text-muted">Invested Amount:</span>
                            <span class="fw-bold text-success font-monospace">${{ number_format($hasActive ? $latestActiveInv->amount : 0, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Daily Profit:</span>
                            <span class="fw-bold text-orange font-monospace">
                                ${{ number_format($hasActive ? (($latestActiveInv->amount * ($latestActiveInv->daily_roi_percent ?? 1)) / 100) : 0, 2) }} 
                                ({{ $hasActive ? $latestActiveInv->daily_roi_percent : 0 }}%)
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('dashboard.investments') }}" class="btn-fg-dark w-100 justify-content-center text-center py-2 text-decoration-none">
                        <i class="bi bi-plus-circle me-1"></i> {{ $hasActive ? 'Upgrade / Add Investment' : 'Choose an Investment Plan' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Side Cards: Earnings Overview Chart -->
    <div class="col-lg-6" data-aos="fade-left">
        <div class="gauge-card d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold font-heading mb-0 text-dark">
                        <i class="bi bi-graph-up text-primary me-2"></i> Earnings Overview
                    </h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-1 small">7 Days Live</span>
                </div>
                <p class="text-muted small mb-3">Real-time daily return trends and automated yields from active contracts.</p>

                <!-- Chart Container -->
                <div style="height: 190px;">
                    <canvas id="earningsChart"></canvas>
                </div>
            </div>

            <!-- Mini Summary Row -->
            <div class="row g-2 pt-3 border-top mt-3">
                <div class="col-4 text-center">
                    <small class="text-muted text-xs d-block">Referral Bonus</small>
                    <span class="fw-bold text-primary font-monospace">${{ number_format($referralCommission ?? 0, 2) }}</span>
                </div>
                <div class="col-4 text-center border-start border-end">
                    <small class="text-muted text-xs d-block">Salary Balance</small>
                    <span class="fw-bold text-warning font-monospace">${{ number_format($wallet->salary_balance ?? 0, 2) }}</span>
                </div>
                <div class="col-4 text-center">
                    <small class="text-muted text-xs d-block">Deposit Wallet</small>
                    <span class="fw-bold text-success font-monospace">${{ number_format($wallet->deposit_balance ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row: 4 Quick Action Cards (Reference Theme Bottom Grid) -->
<div class="row g-3 mb-4" data-aos="fade-up">
    <!-- Action 1: Deposit -->
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('dashboard.deposits') }}" class="action-card">
            <div class="action-icon box-green">
                <i class="bi bi-wallet2"></i>
            </div>
            <div>
                <div class="action-title">Deposit</div>
                <p class="action-desc">Add funds to your deposit wallet</p>
            </div>
        </a>
    </div>

    <!-- Action 2: Invest Now -->
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('dashboard.investments') }}" class="action-card">
            <div class="action-icon box-orange">
                <i class="bi bi-rocket-takeoff"></i>
            </div>
            <div>
                <div class="action-title">Invest Now</div>
                <p class="action-desc">Choose a plan & start earning</p>
            </div>
        </a>
    </div>

    <!-- Action 3: Withdraw -->
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('dashboard.withdrawals') }}" class="action-card">
            <div class="action-icon box-green">
                <i class="bi bi-arrow-up-right-circle"></i>
            </div>
            <div>
                <div class="action-title">Withdraw</div>
                <p class="action-desc">Disburse your earnings to USDT</p>
            </div>
        </a>
    </div>

    <!-- Action 4: Transactions / Salary -->
    <div class="col-md-6 col-lg-3">
        <a href="{{ route('dashboard.history') }}" class="action-card">
            <div class="action-icon box-orange">
                <i class="bi bi-journal-text"></i>
            </div>
            <div>
                <div class="action-title">Transactions</div>
                <p class="action-desc">View full financial ledger</p>
            </div>
        </a>
    </div>
</div>

<!-- Leadership Salary & Team Referral Career Banner -->
<div class="row g-4 mb-4">
    <!-- Salary Tier Progress Card -->
    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="50">
        <div class="gauge-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold font-heading mb-0 text-dark">
                    <i class="bi bi-award-fill text-warning me-2"></i> Monthly Leadership Salary
                </h5>
                @if(isset($salaryEligibility) && $salaryEligibility['is_eligible'])
                    <span class="badge bg-success rounded-pill px-3 py-1">QUALIFIED</span>
                @else
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-1">IN PROGRESS</span>
                @endif
            </div>

            <p class="text-muted small mb-3">
                Earn up to <strong>$300/month</strong> recurring leadership salary with active qualifying direct members (min $50 active investment).
            </p>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <small class="text-muted text-xs d-block text-uppercase fw-bold">Qualifying Directs</small>
                        <h4 class="fw-bold text-dark font-monospace mb-0">{{ $salaryEligibility['qualifying_directs'] ?? 0 }} Members</h4>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-3 border">
                        <small class="text-muted text-xs d-block text-uppercase fw-bold">Qualified Level</small>
                        <h4 class="fw-bold text-success mb-0">
                            {{ isset($salaryEligibility['qualified_level']) ? $salaryEligibility['qualified_level']->name : 'None' }}
                            <small class="text-muted fs-6">(${{ isset($salaryEligibility['qualified_level']) ? number_format($salaryEligibility['qualified_level']->monthly_salary, 0) : 0 }}/mo)</small>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                @if(isset($salaryEligibility) && $salaryEligibility['can_claim'])
                    <form action="{{ route('dashboard.salary.claim') }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-fg-orange w-100 fw-bold">
                            <i class="bi bi-gift-fill me-1"></i> Claim Monthly Salary (${{ number_format($salaryEligibility['salary_amount'], 2) }})
                        </button>
                    </form>
                @else
                    <a href="{{ route('dashboard.salary') }}" class="btn btn-outline-secondary rounded-pill w-100 fw-bold">
                        <i class="bi bi-info-circle me-1"></i> View Salary Career Tiers
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Referral Team & Instant Link Card -->
    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
        <div class="gauge-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold font-heading mb-0 text-dark">
                    <i class="bi bi-people-fill text-primary me-2"></i> 10-Level Referral Matrix
                </h5>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 fw-bold">20% Direct Reward</span>
            </div>

            <p class="text-muted small mb-3">
                Share your personal link to earn instant 20% direct sponsor rewards plus 10 levels of multi-tier network commissions.
            </p>

            <div class="input-group mb-3">
                <input type="text" class="form-control bg-light rounded-start-pill border font-monospace text-muted small" 
                       value="{{ url('/register?ref=' . auth()->user()->referral_code) }}" id="refInput" readonly>
                <button class="btn btn-fg-primary rounded-end-pill px-4 fw-bold" onclick="copyToClipboard(document.getElementById('refInput').value, this)">
                    <i class="bi bi-clipboard me-1"></i> Copy Link
                </button>
            </div>

            <div class="row g-2 text-center">
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3 border">
                        <small class="text-muted text-[11px] d-block text-uppercase">Direct Referrals</small>
                        <span class="fw-bold font-monospace text-dark">{{ $directReferralsCount ?? 0 }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3 border">
                        <small class="text-muted text-[11px] d-block text-uppercase">Team Size (10 Lvl)</small>
                        <span class="fw-bold font-monospace text-primary">{{ $teamSize ?? 0 }}</span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="p-2 bg-light rounded-3 border">
                        <small class="text-muted text-[11px] d-block text-uppercase">Team Business</small>
                        <span class="fw-bold font-monospace text-success">${{ number_format($teamVolume ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Real Database Live Activity Ticker / Notifications -->
@if(isset($recentActivities) && $recentActivities->isNotEmpty())
<div class="gauge-card mb-4" data-aos="fade-up">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
            <span class="running-dot"></span> Live Platform Activities (Real Database Events)
        </h6>
        <span class="badge bg-light text-muted border rounded-pill px-2 py-1 text-xs">Privacy-Preserved</span>
    </div>

    <div class="row g-2">
        @foreach($recentActivities as $act)
            @php
                $uName = $act->user ? (substr($act->user->name, 0, 1) . '***' . substr($act->user->name, -1)) : 'Client';
            @endphp
            <div class="col-md-6 col-lg-3">
                <div class="p-2 rounded-3 border bg-light d-flex align-items-center justify-content-between">
                    <div>
                        <span class="fw-bold small text-dark d-block">{{ $uName }}</span>
                        <span class="text-muted text-[11px] text-capitalize">{{ $act->type }}</span>
                    </div>
                    <span class="fw-bold font-monospace small {{ in_array($act->type, ['deposit', 'roi', 'commission', 'salary']) ? 'text-success' : 'text-danger' }}">
                        +${{ number_format($act->amount, 2) }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    // Live Countdown Timer for Next ROI Cycle
    function initCountdown() {
        const timerEl = document.getElementById('roiCountdown');
        if (!timerEl) return;

        // Count down to next midnight UTC or next 24h interval
        const now = new Date();
        const nextTarget = new Date(now.getFullYear(), now.getMonth(), now.getDate() + 1, 0, 0, 0);

        function update() {
            const current = new Date();
            let diff = Math.max(0, Math.floor((nextTarget - current) / 1000));

            const hours = String(Math.floor(diff / 3600)).padStart(2, '0');
            diff %= 3600;
            const minutes = String(Math.floor(diff / 60)).padStart(2, '0');
            const seconds = String(diff % 60).padStart(2, '0');

            timerEl.textContent = `${hours} : ${minutes} : ${seconds}`;
        }

        update();
        setInterval(update, 1000);
    }

    // Chart.js Smooth Earnings Wave Chart (Reference Theme Style)
    function initEarningsChart() {
        const ctx = document.getElementById('earningsChart');
        if (!ctx) return;

        const labels = {!! json_encode($chartLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!};
        const dataRois = {!! json_encode($chartRois ?? [0, 0, 0, 0, 0, 0, 0]) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily ROI ($)',
                    data: dataRois,
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        padding: 10,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    },
                    y: {
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            callback: function(val) { return '$' + val; }
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initCountdown();
        initEarningsChart();
    });
</script>
@endpush
