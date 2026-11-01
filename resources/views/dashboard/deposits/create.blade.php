@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" data-aos="fade-down">
    <div>
        <h2 class="fw-bold mb-1 text-dark font-heading">
            <i class="bi bi-wallet2 text-success me-2"></i> Deposit Capital
        </h2>
        <p class="text-muted small mb-0">Send USDT to the address below, then submit your transaction ID for administrator verification.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dashboard.history', ['type' => 'deposit']) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-receipt me-1"></i> Deposit History
        </a>
    </div>
</div>

<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-7 col-md-10">
        <div class="fg-card p-4 p-md-5 shadow-sm">

            {{-- Important notice --}}
            <div class="alert bg-warning bg-opacity-10 border border-warning border-opacity-25 text-dark small rounded-3 mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill text-warning fs-5 flex-shrink-0"></i>
                <div>
                    <strong>Manual Deposit — Admin Approval Required.</strong><br>
                    Send USDT to the address shown below, then submit your TXID. Your balance will be credited <strong>only after administrator approval</strong>. Do not send any currency other than USDT on the selected network.
                </div>
            </div>

            @if(!$trc20Enabled && !$bep20Enabled)
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    No deposit networks are currently enabled. Please contact support.
                </div>
            @else

            {{-- Network selection tabs --}}
            @if($trc20Enabled && $bep20Enabled)
            <ul class="nav nav-pills mb-4 nav-fill gap-2" id="networkTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill fw-bold py-2 px-3 border" id="trc20-tab"
                        data-bs-toggle="pill" data-bs-target="#trc20-panel" type="button" role="tab">
                        <i class="bi bi-cpu text-success me-1"></i> USDT (TRC20)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill fw-bold py-2 px-3 border" id="bep20-tab"
                        data-bs-toggle="pill" data-bs-target="#bep20-panel" type="button" role="tab">
                        <i class="bi bi-shield-fill-check text-warning me-1"></i> USDT (BEP20)
                    </button>
                </li>
            </ul>
            @endif

            <div class="tab-content" id="networkTabContent">

                {{-- TRC20 Panel --}}
                @if($trc20Enabled)
                <div class="tab-pane fade {{ $trc20Enabled ? 'show active' : '' }}" id="trc20-panel" role="tabpanel">
                    <div class="mb-4 text-center p-4 rounded-4 bg-light border">
                        @if($trc20Address)
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($trc20Address) }}&color=062810&bgcolor=f8fafc"
                            class="img-fluid rounded-3 mb-3 border shadow-sm" alt="TRC20 QR Code">
                        @endif
                        <h6 class="text-muted text-uppercase text-xs fw-bold mb-2">
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2">USDT (TRC20) — Tron Network</span>
                        </h6>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control text-center font-monospace bg-white"
                                value="{{ $trc20Address ?: 'Address not configured — contact admin' }}"
                                id="trc20Addr" readonly>
                            @if($trc20Address)
                            <button class="btn btn-outline-success" type="button"
                                onclick="navigator.clipboard.writeText('{{ $trc20Address }}').then(()=>{this.innerHTML='<i class=\'bi bi-check2\'></i>';setTimeout(()=>{this.innerHTML='<i class=\'bi bi-copy\'></i>'},2000)})">
                                <i class="bi bi-copy"></i>
                            </button>
                            @endif
                        </div>
                        <small class="text-danger fw-bold d-block mt-2">
                            ⚠ Only send USDT on the TRC20 (Tron) network. Sending on wrong network will result in permanent loss.
                        </small>
                    </div>

                    <form action="{{ route('dashboard.deposits.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="network" value="trc20">
                        @include('dashboard.deposits._form', ['min' => $minDeposit, 'max' => $maxDeposit])
                    </form>
                </div>
                @endif

                {{-- BEP20 Panel --}}
                @if($bep20Enabled)
                <div class="tab-pane fade {{ !$trc20Enabled ? 'show active' : '' }}" id="bep20-panel" role="tabpanel">
                    <div class="mb-4 text-center p-4 rounded-4 bg-light border">
                        @if($bep20Address)
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($bep20Address) }}&color=062810&bgcolor=f8fafc"
                            class="img-fluid rounded-3 mb-3 border shadow-sm" alt="BEP20 QR Code">
                        @endif
                        <h6 class="text-muted text-uppercase text-xs fw-bold mb-2">
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2">USDT (BEP20) — BNB Smart Chain</span>
                        </h6>
                        <div class="input-group mt-2">
                            <input type="text" class="form-control text-center font-monospace bg-white"
                                value="{{ $bep20Address ?: 'Address not configured — contact admin' }}"
                                id="bep20Addr" readonly>
                            @if($bep20Address)
                            <button class="btn btn-outline-warning" type="button"
                                onclick="navigator.clipboard.writeText('{{ $bep20Address }}').then(()=>{this.innerHTML='<i class=\'bi bi-check2\'></i>';setTimeout(()=>{this.innerHTML='<i class=\'bi bi-copy\'></i>'},2000)})">
                                <i class="bi bi-copy"></i>
                            </button>
                            @endif
                        </div>
                        <small class="text-danger fw-bold d-block mt-2">
                            ⚠ Only send USDT on BEP20 (BNB Smart Chain). Sending on wrong network will result in permanent loss.
                        </small>
                    </div>

                    <form action="{{ route('dashboard.deposits.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="network" value="bep20">
                        @include('dashboard.deposits._form', ['min' => $minDeposit, 'max' => $maxDeposit])
                    </form>
                </div>
                @endif

            </div>
            @endif

        </div>
    </div>
</div>
@endsection
