@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-dark font-heading">
            <i class="bi bi-wallet2 text-success me-2"></i> Deposit Capital
        </h2>
        <p class="text-muted small mb-0">Credit funds instantly into your Deposit Wallet via automated or manual USDT (TRC20/BEP20).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard.history', ['type' => 'deposit']) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-receipt me-1"></i> Deposit History
        </a>
    </div>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-7 col-md-10">
        <div class="fg-card p-4 p-md-5 shadow-sm border-emerald-500 border-opacity-30">
            <div class="alert bg-success bg-opacity-10 border border-success border-opacity-25 text-dark small rounded-3 mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-shield-check text-success fs-5"></i>
                <div>{{ setting('deposit_instructions', 'Only send USDT (TRC20/BEP20) to this address. Transactions verify automatically.') }}</div>
            </div>

            @php
                $autoEnabled = setting('nowpayments_enabled', 1) == 1;
                $autoMin = setting('nowpayments_min_deposit', setting('min_deposit', 25));
                $autoMax = setting('nowpayments_max_deposit', setting('max_deposit', 50000));
            @endphp

            @if($autoEnabled)
            <ul class="nav nav-pills mb-4 nav-fill gap-2" id="depositTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold py-2.5 px-3 border" id="auto-tab" data-bs-toggle="pill" data-bs-target="#auto-deposit" type="button" role="tab">
                        <i class="bi bi-cpu-fill text-success me-1"></i> Instant Auto Gateway
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold py-2.5 px-3 border" id="manual-tab" data-bs-toggle="pill" data-bs-target="#manual-deposit" type="button" role="tab">
                        <i class="bi bi-qr-code text-warning me-1"></i> Direct Transfer
                    </button>
                </li>
            </ul>
            @endif

            <div class="tab-content" id="depositTabContent">
                <!-- Automatic Deposit -->
                @if($autoEnabled)
                <div class="tab-pane fade show active" id="auto-deposit" role="tabpanel">
                    <form action="{{ route('dashboard.deposits.nowpayments') }}" method="POST">
                        @csrf
                        
                        @php
                            $trc20Enabled = setting('nowpayments_enable_trc20', 1) == 1;
                            $bep20Enabled = setting('nowpayments_enable_bep20', 1) == 1;
                            $defaultNetwork = setting('nowpayments_default_network', 'trc20');
                        @endphp

                        @if($trc20Enabled || $bep20Enabled)
                        <div class="mb-4">
                            <label class="form-label text-muted text-xs text-uppercase fw-bold">Select Network</label>
                            <div class="d-flex gap-3">
                                @if($trc20Enabled)
                                <div class="form-check flex-grow-1 p-0">
                                    <input type="radio" class="btn-check" name="network" id="net-trc20" value="trc20" {{ $defaultNetwork === 'trc20' || !$bep20Enabled ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-success w-100 py-2.5 rounded-3 fw-bold" for="net-trc20">
                                        <i class="bi bi-cpu me-1"></i> USDT (TRC20)
                                    </label>
                                </div>
                                @endif
                                @if($bep20Enabled)
                                <div class="form-check flex-grow-1 p-0">
                                    <input type="radio" class="btn-check" name="network" id="net-bep20" value="bep20" {{ $defaultNetwork === 'bep20' || !$trc20Enabled ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-warning w-100 py-2.5 rounded-3 fw-bold" for="net-bep20">
                                        <i class="bi bi-shield-fill-check me-1"></i> USDT (BEP20)
                                    </label>
                                </div>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="alert alert-danger mb-4">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> No USDT networks are currently enabled by administrator.
                        </div>
                        @endif

                        <div class="mb-4">
                            <label class="form-label text-muted text-xs text-uppercase fw-bold">
                                Deposit Amount (USD) <span class="text-success fw-normal">(Min: ${{ $autoMin }} | Max: ${{ $autoMax }})</span>
                            </label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light text-muted fw-bold">$</span>
                                <input type="number" step="0.01" min="{{ $autoMin }}" max="{{ $autoMax }}" name="amount" class="form-control" placeholder="Enter amount to deposit..." required>
                            </div>
                            <small class="text-muted mt-2 d-block"><i class="bi bi-shield-check text-success me-1"></i> Automated gateway via NOWPayments. Instant on-chain confirmation.</small>
                        </div>
                        <button type="submit" class="btn btn-fg-primary w-100 py-2.5 fw-bold shadow-md">
                            <i class="bi bi-lightning-charge me-1"></i> Proceed to Payment Gateway
                        </button>
                    </form>
                </div>
                @endif

                <!-- Manual Deposit -->
                <div class="tab-pane fade {{ !$autoEnabled ? 'show active' : '' }}" id="manual-deposit" role="tabpanel">
                    <div class="mb-4 text-center p-4 rounded-4 bg-light border">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $adminAddress }}&color=062810&bgcolor=f8fafc" class="img-fluid rounded-3 mb-3 border shadow-sm" alt="QR Code">
                        <h6 class="text-muted text-uppercase text-xs fw-bold mb-1">Company USDT (TRC20) Deposit Address</h6>
                        <div class="input-group">
                            <input type="text" class="form-control text-center font-monospace bg-white" value="{{ $adminAddress }}" id="walletAddr" readonly>
                            <button class="btn btn-outline-success" type="button" onclick="navigator.clipboard.writeText('{{ $adminAddress }}'); alert('Address copied to clipboard!');">
                                <i class="bi bi-copy"></i>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('dashboard.deposits.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted text-xs text-uppercase fw-bold">
                                Deposit Amount (USD) <span class="text-success fw-normal">(Min: ${{ setting('min_deposit', 25) }} | Max: ${{ setting('max_deposit', 50000) }})</span>
                            </label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-light text-muted fw-bold">$</span>
                                <input type="number" step="0.01" min="{{ setting('min_deposit', 25) }}" max="{{ setting('max_deposit', 50000) }}" name="amount" class="form-control" required placeholder="Amount transferred">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted text-xs text-uppercase fw-bold">Transaction Hash (TXID)</label>
                            <input type="text" name="txid" class="form-control font-monospace" placeholder="Enter the 64-character hash from your wallet" required>
                        </div>
                        <button type="submit" class="btn btn-fg-primary w-100 py-2.5 fw-bold shadow-md">
                            <i class="bi bi-check2-circle me-1"></i> Submit Deposit for Verification
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
