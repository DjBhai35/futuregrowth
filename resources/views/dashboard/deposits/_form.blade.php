{{-- Shared deposit form fields --}}
@if ($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0 small">@foreach ($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
    </div>
@endif

<div class="mb-3">
    <label class="form-label text-muted text-xs text-uppercase fw-bold">
        Deposit Amount (USDT) <span class="text-success fw-normal">(Min: ${{ $min }} | Max: ${{ number_format($max, 0) }})</span>
    </label>
    <div class="input-group shadow-sm">
        <span class="input-group-text bg-light text-muted fw-bold">$</span>
        <input type="number" step="0.01" min="{{ $min }}" max="{{ $max }}"
            name="amount" class="form-control @error('amount') is-invalid @enderror"
            placeholder="Enter amount you transferred" required value="{{ old('amount') }}">
        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label text-muted text-xs text-uppercase fw-bold">Transaction Hash (TXID)</label>
    <input type="text" name="txid"
        class="form-control font-monospace @error('txid') is-invalid @enderror"
        placeholder="Paste the transaction hash from your wallet/explorer"
        required value="{{ old('txid') }}">
    @error('txid')<div class="invalid-feedback">{{ $message }}</div>@enderror
    <small class="text-muted">Copy the TXID from your wallet app or blockchain explorer after sending.</small>
</div>

<div class="mb-4">
    <label class="form-label text-muted text-xs text-uppercase fw-bold">
        Transaction Proof Screenshot <span class="text-muted fw-normal">(Optional — JPG/PNG, max 5MB)</span>
    </label>
    <input type="file" name="proof" class="form-control @error('proof') is-invalid @enderror"
        accept="image/jpeg,image/png,image/gif,image/webp">
    @error('proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="alert bg-success bg-opacity-10 border border-success border-opacity-25 text-dark small rounded-3 mb-3 d-flex gap-2">
    <i class="bi bi-clock-history text-success flex-shrink-0 mt-1"></i>
    <span>Your deposit will be <strong>manually reviewed</strong> by our team. Balance is credited only after approval. Processing typically takes 1–24 hours.</span>
</div>

<button type="submit" class="btn btn-fg-primary w-100 py-2 fw-bold shadow-md">
    <i class="bi bi-check2-circle me-1"></i> Submit Deposit for Verification
</button>
