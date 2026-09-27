@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-dark font-heading">
            <i class="bi bi-arrow-up-right-circle text-danger me-2"></i> Withdraw Earnings
        </h2>
        <p class="text-muted small mb-0">Request an authenticated payout of your ROI or referral balances directly to your personal USDT (TRC20) address.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard.history', ['type' => 'withdrawal']) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-receipt me-1"></i> Withdrawal History
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 fg-card border-success border-opacity-50 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-4 fg-card border-danger border-opacity-50 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i> {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-6 col-md-9">
        <div class="fg-card p-4 p-md-5 shadow-sm border-emerald-500 border-opacity-30">
            <!-- Processing Policy Banner -->
            <div class="mb-4 p-3 rounded-3 border border-warning border-opacity-30 bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                <div>
                    <strong class="d-block small text-dark"><i class="bi bi-shield-check text-warning me-1"></i> Security Policy</strong>
                    <span class="text-muted text-xs">Payout requests undergo cryptographic validation.</span>
                </div>
                <div class="text-end">
                    <span class="text-muted text-[11px] d-block text-uppercase fw-bold">Processing</span>
                    <strong class="text-dark small">Automated / Daily</strong>
                </div>
            </div>

            <!-- Available Balances Pill -->
            <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded-3 bg-light border">
                <span class="text-muted small fw-bold">Total Withdrawable:</span>
                <span class="fw-bold text-success fs-5 font-monospace">${{ number_format($totalAvailable, 2) }}</span>
            </div>

            <form action="{{ route('dashboard.withdrawals.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted text-xs text-uppercase fw-bold">Select Origin Wallet</label>
                    <select name="balance_type" class="form-select" required>
                        <option value="roi_balance">ROI Wallet (${{ number_format(auth()->user()->wallet->roi_balance, 2) }})</option>
                        <option value="referral_balance">Referral Wallet (${{ number_format(auth()->user()->wallet->referral_balance, 2) }})</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted text-xs text-uppercase fw-bold">
                        Withdrawal Amount (USD)
                        <span class="text-warning fw-normal">(Min: ${{ $minWithdrawal }} | Max: ${{ $maxWithdrawal }} | Fee: {{ $feePercent }}%)</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light text-muted fw-bold">$</span>
                        <input type="number" step="0.01" min="{{ $minWithdrawal }}" max="{{ $maxWithdrawal }}" name="amount" class="form-control" placeholder="Enter amount to withdraw..." required>
                    </div>
                    <small class="text-muted text-[11px] mt-1 d-block">
                        <i class="bi bi-info-circle me-1"></i> A fee of {{ $feePercent }}% is automatically applied to cover network gas fees.
                    </small>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted text-xs text-uppercase fw-bold">Your USDT (TRC20) Destination Address</label>
                    <input type="text" name="wallet_address" class="form-control font-monospace" placeholder="T..." required>
                </div>

                <button type="submit" class="btn btn-fg-primary w-100 py-2.5 fw-bold shadow-md">
                    <i class="bi bi-arrow-up-circle me-1"></i> Confirm Withdrawal Request
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
