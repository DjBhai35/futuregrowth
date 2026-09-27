@extends('layouts.app')

@section('content')
<div class="row justify-content-center" data-aos="fade-up">
    <div class="col-lg-8 col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-dark font-heading">
                    <i class="bi bi-person-circle text-success me-2"></i> Account Profile
                </h2>
                <p class="text-muted small mb-0">Manage your personal information and profile picture.</p>
            </div>
            <a href="{{ route('dashboard.settings') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-shield-lock me-1"></i> Security Settings
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 fg-card border-success border-opacity-50 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 text-success"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="fg-card p-4 p-md-5 border-emerald-500 border-opacity-30 shadow-sm">
            <div class="text-center mb-5">
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle d-block mx-auto mb-3 border border-2 border-success shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm mx-auto mb-3 text-white" style="width: 90px; height: 90px; font-size: 2.25rem; background: linear-gradient(135deg, var(--fg-orange), var(--fg-orange-hover));">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <h4 class="fw-bold text-dark font-heading mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted small mb-2">Member since {{ auth()->user()->created_at->format('M Y') }} • <span class="text-dark font-monospace">@ {{ auth()->user()->username }}</span></p>
                <div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 rounded-pill text-xs fw-bold">
                        <i class="bi bi-shield-check me-1"></i> Active Member
                    </span>
                </div>
            </div>

            <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-12 mb-2">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Upload New Avatar</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*">
                        <small class="text-muted text-[11px]">Supported formats: JPG, PNG, WEBP. Max 2MB.</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Full Name</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-person text-success"></i></span>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Username</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-at text-muted"></i></span>
                            <input type="text" class="form-control bg-light text-muted" value="{{ auth()->user()->username }}" readonly disabled>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Email Address</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-envelope text-success"></i></span>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted text-xs text-uppercase fw-bold">Phone Number</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="bi bi-telephone text-success"></i></span>
                            <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}" required>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-4 pt-3 border-top text-end">
                        <button type="submit" class="btn btn-fg-primary px-4 fw-bold shadow-sm">
                            <i class="bi bi-check2 me-1"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
