@extends('layouts.app')

@section('content')
<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8 col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark font-heading">
                    <i class="bi bi-shield-lock text-success me-2"></i> Security & Preferences
                </h2>
                <p class="text-muted small mb-0">Update your security credentials and alert configurations.</p>
            </div>
            <a href="{{ route('dashboard.profile') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-person me-1"></i> Profile Info
            </a>
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

        <!-- Change Password Card -->
        <div class="fg-card p-4 p-md-5 border-emerald-500 border-opacity-30 shadow-sm mb-4">
            <h5 class="fw-bold text-dark font-heading border-bottom pb-3 mb-4">
                <i class="bi bi-key-fill text-warning me-2"></i> Update Password
            </h5>
            
            <form action="{{ route('dashboard.password.update') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label text-muted text-xs text-uppercase fw-bold">Current Password</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text"><i class="bi bi-unlock text-warning"></i></span>
                        <input type="password" name="current_password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">New Password</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-lock text-success"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required minlength="8">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Confirm New Password</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-shield-check text-success"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required minlength="8">
                        </div>
                    </div>
                </div>
                
                <div class="text-end">
                    <button type="submit" class="btn btn-fg-primary px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2 me-1"></i> Update Security Credentials
                    </button>
                </div>
            </form>
        </div>

        <!-- Notification Preferences Card -->
        <div class="fg-card p-4 p-md-5 shadow-sm">
            <h5 class="fw-bold text-dark font-heading mb-3">
                <i class="bi bi-bell text-info me-2"></i> Notification Preferences
            </h5>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                <label class="form-check-label text-dark small" for="emailNotif">Email notifications for approved deposits and processed withdrawals</label>
            </div>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="roiNotif" checked>
                <label class="form-check-label text-dark small" for="roiNotif">Daily ROI distribution alerts and notifications</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="teamNotif" checked>
                <label class="form-check-label text-dark small" for="teamNotif">New direct team member registration alerts</label>
            </div>
        </div>
    </div>
</div>
@endsection
