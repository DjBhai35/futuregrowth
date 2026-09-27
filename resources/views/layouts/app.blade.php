<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('seo_meta_title', setting('site_name', config('app.name', 'FutureGrowth.tech'))) }}</title>
    <meta name="description" content="{{ setting('seo_meta_description', 'Intelligent Automated USDT Daily ROI & Multi-Tier Leadership Platform') }}">
    
    <!-- Favicon -->
    @if(setting('site_favicon'))
        <link rel="icon" href="{{ Storage::url(setting('site_favicon')) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2316a34a'><path d='M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5'/></svg>">
    @endif

    <!-- Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --fg-forest: #062810;
            --fg-forest-light: #0a3d19;
            --fg-emerald: #16a34a;
            --fg-emerald-glow: rgba(22, 163, 74, 0.35);
            --fg-orange: #f97316;
            --fg-orange-hover: #ea580c;
            --fg-orange-glow: rgba(249, 115, 22, 0.4);
            --fg-bg-light: #f4f7f4;
            --fg-card-bg: #ffffff;
            --fg-text-dark: #0f172a;
            --fg-text-muted: #64748b;
            --fg-border: #e2e8f0;
            --fg-border-light: rgba(22, 163, 74, 0.12);
            --fg-radius: 1.25rem;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--fg-bg-light);
            color: var(--fg-text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 12% 15%, rgba(22, 163, 74, 0.05), transparent 30%),
                radial-gradient(circle at 88% 20%, rgba(249, 115, 22, 0.04), transparent 25%),
                radial-gradient(circle at 50% 85%, rgba(22, 163, 74, 0.04), transparent 40%);
            background-attachment: fixed;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', 'Inter', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Layout Container */
        .app-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Deep Forest Green Sidebar (as in reference image) */
        .app-sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--fg-forest) 0%, #041c0b 100%);
            color: #ffffff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1030;
            overflow-y: auto;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border-right: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 1.5rem 1.5rem 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--fg-emerald), #22c55e);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.35rem;
            box-shadow: 0 6px 16px var(--fg-emerald-glow);
        }

        .sidebar-brand-text {
            font-family: 'Outfit', sans-serif;
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.1;
        }
        .sidebar-brand-text span {
            color: var(--fg-orange);
        }

        .sidebar-nav {
            padding: 1.25rem 0.85rem;
            list-style: none;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-nav-item {
            margin-bottom: 0.35rem;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.925rem;
            transition: all 0.2s ease;
        }

        .sidebar-nav-link i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
            transition: transform 0.2s;
        }

        .sidebar-nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .sidebar-nav-link:hover i {
            transform: scale(1.1);
        }

        /* Active Sidebar Link (Radiant Orange pill from reference image) */
        .sidebar-nav-link.active {
            background: linear-gradient(135deg, var(--fg-orange) 0%, var(--fg-orange-hover) 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px var(--fg-orange-glow);
        }

        .sidebar-nav-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            font-weight: 700;
            padding: 1rem 1rem 0.4rem 1rem;
        }

        /* Main Content Wrapper */
        .app-main {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            transition: all 0.3s ease;
        }

        /* Top Header */
        .app-header {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--fg-border);
            padding: 0.85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .header-greeting h4 {
            font-size: 1.35rem;
            font-weight: 800;
            margin: 0;
            color: #0f172a;
        }

        .header-greeting p {
            margin: 0;
            font-size: 0.85rem;
            color: var(--fg-text-muted);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* User Avatar Pill */
        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.35rem 0.85rem 0.35rem 0.4rem;
            background: #ffffff;
            border: 1px solid var(--fg-border);
            border-radius: 9999px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            color: inherit;
        }

        .user-pill:hover {
            border-color: var(--fg-emerald);
            transform: translateY(-1px);
        }

        .user-avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--fg-orange), #fb923c);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            object-fit: cover;
        }

        /* Notification Bell */
        .notification-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid var(--fg-border);
            background: #ffffff;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-btn:hover {
            color: var(--fg-emerald);
            border-color: var(--fg-emerald);
            transform: translateY(-1px);
        }

        .notification-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }

        /* Page Content Area */
        .app-content {
            padding: 1.75rem;
            flex-grow: 1;
        }

        /* Modern White Cards (Reference Theme) */
        .fg-card, .glass-card {
            background: var(--fg-card-bg);
            border: 1px solid var(--fg-border);
            border-radius: var(--fg-radius);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fg-card:hover, .glass-card:hover {
            border-color: rgba(22, 163, 74, 0.25);
            box-shadow: 0 12px 30px -4px rgba(22, 163, 74, 0.1);
        }

        /* Custom Buttons */
        .btn-fg-primary {
            background: linear-gradient(135deg, var(--fg-emerald) 0%, #15803d 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.65rem 1.4rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px var(--fg-emerald-glow);
        }

        .btn-fg-primary:hover {
            background: linear-gradient(135deg, #15803d 0%, #166534 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--fg-emerald-glow);
        }

        .btn-fg-orange {
            background: linear-gradient(135deg, var(--fg-orange) 0%, var(--fg-orange-hover) 100%);
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.65rem 1.4rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px var(--fg-orange-glow);
        }

        .btn-fg-orange:hover {
            background: linear-gradient(135deg, var(--fg-orange-hover) 0%, #c2410c 100%);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--fg-orange-glow);
        }

        .btn-fg-dark {
            background: #0f172a;
            border: 1px solid #1e293b;
            color: #ffffff;
            font-weight: 600;
            border-radius: 9999px;
            padding: 0.65rem 1.4rem;
            transition: all 0.2s ease;
        }

        .btn-fg-dark:hover {
            background: #020617;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        /* Mobile Bottom Nav (Reference Image top-right phone view) */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(180deg, var(--fg-forest) 0%, #031407 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.5rem 0.75rem;
            z-index: 1045;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.25);
        }

        .mobile-nav-item {
            flex: 1;
            text-align: center;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.725rem;
            font-weight: 600;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            padding: 0.35rem 0.25rem;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .mobile-nav-item i {
            font-size: 1.35rem;
        }

        .mobile-nav-item.active {
            color: var(--fg-orange) !important;
        }

        .mobile-nav-item:hover {
            color: #ffffff;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .app-sidebar {
                transform: translateX(-100%);
            }
            .app-sidebar.show {
                transform: translateX(0);
            }
            .app-main {
                margin-left: 0;
                width: 100%;
                padding-bottom: 75px; /* space for mobile nav */
            }
            .mobile-bottom-nav {
                display: flex;
                justify-content: space-around;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 1025;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }
        /* Universal Modern Form Controls */
        .form-control, .form-select {
            background-color: #ffffff !important;
            border: 1px solid #cbd5e1 !important;
            color: #0f172a !important;
            border-radius: 0.75rem !important;
            padding: 0.625rem 0.95rem;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--fg-emerald) !important;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.15) !important;
            outline: none;
        }
        .form-control::placeholder {
            color: #94a3b8 !important;
        }
        .input-group-text {
            background-color: #f8fafc !important;
            border: 1px solid #cbd5e1 !important;
            color: #64748b !important;
            border-radius: 0.75rem;
        }
        .input-group > .input-group-text:first-child {
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        .input-group > .form-control:not(:first-child) {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }

        /* Modern Table Harmonization */
        .table {
            color: #334155;
            vertical-align: middle;
        }
        .table-dark {
            background-color: #ffffff !important;
            color: #1e293b !important;
            --bs-table-bg: #ffffff;
            --bs-table-striped-bg: #f8fafc;
            --bs-table-hover-bg: #f1f5f9;
            --bs-table-color: #1e293b;
        }
        .table-dark th {
            background-color: #f8fafc !important;
            color: #64748b !important;
            font-weight: 700;
            border-bottom: 1px solid #e2e8f0 !important;
        }
        .table-dark td {
            background-color: transparent !important;
            color: #1e293b !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }
        .table-dark tbody tr:hover td {
            background-color: #f8fafc !important;
        }

        /* Legacy Button Fallbacks */
        .btn-premium {
            background: linear-gradient(135deg, var(--fg-orange) 0%, var(--fg-orange-hover) 100%) !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            border: none !important;
            border-radius: 9999px !important;
            box-shadow: 0 4px 14px var(--fg-orange-glow);
            transition: all 0.2s ease;
        }
        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px var(--fg-orange-glow);
            color: #ffffff !important;
        }

        /* Responsive Table Container */
        .table-responsive {
            border-radius: 0.75rem;
            -webkit-overflow-scrolling: touch;
        }

        /* Modal Harmonization */
        .modal-content {
            background-color: #ffffff !important;
            color: #0f172a !important;
            border-radius: 1.25rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.15);
        }

        /* Smart Text-White Adaptation for Light Canvas */
        .text-white:not(.btn):not(.btn *):not(.badge):not(.badge *):not(.alert):not(.alert *):not(.salary-hero-card *):not(.app-sidebar *):not(.mobile-bottom-nav *):not(.footer-fg *):not(.plan-badge *) {
            color: #0f172a !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    @auth
    <!-- Mobile Sidebar Backdrop -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <div class="app-wrapper">
        <!-- Deep Forest Green Sidebar (Reference Theme) -->
        <aside class="app-sidebar" id="appSidebar">
            <a href="{{ request()->routeIs('admin.*') ? route('admin.dashboard') : route('dashboard') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-layers-fill"></i>
                </div>
                <div class="sidebar-brand-text">
                    Future<span>Growth</span>
                </div>
            </a>

            <ul class="sidebar-nav">
                @if(request()->routeIs('admin.*'))
                    <!-- Admin Navigation -->
                    <li class="sidebar-nav-heading">Admin Portal</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.transactions') }}" class="sidebar-nav-link {{ request()->routeIs('admin.transactions') ? 'active' : '' }}">
                            <i class="bi bi-journal-text"></i> <span>Global Ledger</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.roi-history') }}" class="sidebar-nav-link {{ request()->routeIs('admin.roi-history') ? 'active' : '' }}">
                            <i class="bi bi-graph-up"></i> <span>ROI Distribution</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.users') }}" class="sidebar-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="bi bi-people"></i> <span>Users</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.referrals') }}" class="sidebar-nav-link {{ request()->routeIs('admin.referrals*') ? 'active' : '' }}">
                            <i class="bi bi-diagram-3"></i> <span>Referral Tree</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.salary') }}" class="sidebar-nav-link {{ request()->routeIs('admin.salary') ? 'active' : '' }}">
                            <i class="bi bi-award"></i> <span>Salary Tiers</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.salary.claims') }}" class="sidebar-nav-link {{ request()->routeIs('admin.salary.claims') ? 'active' : '' }}">
                            <i class="bi bi-cash-stack"></i> <span>Salary Claims</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.deposits') }}" class="sidebar-nav-link {{ request()->routeIs('admin.deposits*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-down-circle"></i> <span>Deposits</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.withdrawals') }}" class="sidebar-nav-link {{ request()->routeIs('admin.withdrawals*') ? 'active' : '' }}">
                            <i class="bi bi-arrow-up-circle"></i> <span>Withdrawals</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.plans') }}" class="sidebar-nav-link {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
                            <i class="bi bi-box"></i> <span>Plans</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.reports') }}" class="sidebar-nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph"></i> <span>Reports</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.tickets') }}" class="sidebar-nav-link {{ request()->routeIs('admin.tickets*') ? 'active' : '' }}">
                            <i class="bi bi-headset"></i> <span>Tickets</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.settings') }}" class="sidebar-nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i> <span>Settings</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-heading">Switch</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-nav-link text-warning">
                            <i class="bi bi-box-arrow-left"></i> <span>User Dashboard</span>
                        </a>
                    </li>
                @else
                    <!-- User Navigation -->
                    <li class="sidebar-nav-heading">Main Menu</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-fill"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard.investments') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard.investments') ? 'active' : '' }}">
                            <i class="bi bi-rocket-takeoff-fill"></i> <span>Investments</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard.deposits') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard.deposits') ? 'active' : '' }}">
                            <i class="bi bi-wallet-fill"></i> <span>Deposit</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard.withdrawals') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard.withdrawals') ? 'active' : '' }}">
                            <i class="bi bi-arrow-up-right-circle-fill"></i> <span>Withdraw</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard.history') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard.history*') ? 'active' : '' }}">
                            <i class="bi bi-journal-text"></i> <span>Transactions</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard.team') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard.team') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i> <span>Referrals</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard.salary') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard.salary') ? 'active' : '' }}">
                            <i class="bi bi-award-fill"></i> <span>Salary</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-heading">Account</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard.profile') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
                            <i class="bi bi-person-fill"></i> <span>Profile</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('dashboard.settings') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard.settings') ? 'active' : '' }}">
                            <i class="bi bi-shield-lock-fill"></i> <span>Security</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#helpCenterModal" class="sidebar-nav-link">
                            <i class="bi bi-headset"></i> <span>Support</span>
                        </a>
                    </li>
                    @if(auth()->user()->is_admin)
                    <li class="sidebar-nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link text-warning">
                            <i class="bi bi-shield-shaded"></i> <span>Admin Panel</span>
                        </a>
                    </li>
                    @endif
                @endif
                <li class="sidebar-nav-item mt-4 pt-3 border-top border-secondary border-opacity-25">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="sidebar-nav-link text-danger w-100 border-0 bg-transparent text-start">
                            <i class="bi bi-power"></i> <span>Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content Canvas -->
        <div class="app-main">
            <!-- Top Header -->
            <header class="app-header">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-secondary d-lg-none rounded-circle" style="width: 40px; height: 40px; padding: 0;" onclick="toggleSidebar()">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div class="header-greeting">
                        <h4>Welcome Back, <span style="color: var(--fg-forest-light);">{{ auth()->user()->name }}</span> 👋</h4>
                        <p>Intelligent USDT Automated ROI & Multi-Tier Platform</p>
                    </div>
                </div>

                <div class="header-actions">
                    <!-- WhatsApp Community Link -->
                    @if(setting('whatsapp_button_enabled', '1') && setting('whatsapp_community_link'))
                        <a href="{{ setting('whatsapp_community_link') }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold d-none d-md-inline-flex align-items-center gap-1">
                            <i class="bi bi-whatsapp"></i> Community
                        </a>
                    @endif

                    <!-- User Total Balance Pill -->
                    @php
                        $topWallet = auth()->user()->wallet;
                        $topTotalBalance = $topWallet ? ($topWallet->deposit_balance + $topWallet->roi_balance + $topWallet->referral_balance + $topWallet->bonus_balance + ($topWallet->salary_balance ?? 0)) : 0;
                    @endphp
                    <div class="d-none d-sm-flex align-items-center gap-2 bg-emerald-50 px-3 py-1 rounded-pill border" style="background: rgba(22, 163, 74, 0.08); border-color: rgba(22, 163, 74, 0.2) !important;">
                        <i class="bi bi-wallet2 text-success"></i>
                        <span class="small text-muted fw-bold">Balance:</span>
                        <span class="fw-bold font-monospace text-success">${{ number_format($topTotalBalance, 2) }}</span>
                    </div>

                    <!-- User Menu Dropdown -->
                    <div class="dropdown">
                        <a href="#" class="user-pill dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-avatar-circle">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span class="fw-bold small d-none d-md-inline">{{ auth()->user()->username ?? auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg rounded-4 p-2 border-0" style="min-width: 220px;">
                            <li><h6 class="dropdown-header text-muted small text-uppercase">Account</h6></li>
                            <li><a class="dropdown-item py-2 rounded-3" href="{{ route('dashboard.profile') }}"><i class="bi bi-person me-2 text-primary"></i> Profile</a></li>
                            <li><a class="dropdown-item py-2 rounded-3" href="{{ route('dashboard.settings') }}"><i class="bi bi-shield-lock me-2 text-success"></i> Security</a></li>
                            <li><a class="dropdown-item py-2 rounded-3" href="{{ route('dashboard.history') }}"><i class="bi bi-journal-text me-2 text-info"></i> Transactions</a></li>
                            <li><hr class="dropdown-divider my-2"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 rounded-3 text-danger fw-bold"><i class="bi bi-power me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Global Toast Container -->
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1060;">
                @if(session('success'))
                    <div class="toast show align-items-center text-white bg-success border-0 rounded-4 shadow" role="alert" data-bs-delay="4000">
                        <div class="d-flex">
                            <div class="toast-body fw-bold">
                                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="toast show align-items-center text-white bg-danger border-0 rounded-4 shadow" role="alert" data-bs-delay="5000">
                        <div class="d-flex">
                            <div class="toast-body fw-bold">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Bottom Navigation (Reference Theme) -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('dashboard') }}" class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('dashboard.investments') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.investments') ? 'active' : '' }}">
            <i class="bi bi-rocket-takeoff-fill"></i>
            <span>Invest</span>
        </a>
        <a href="{{ route('dashboard.salary') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.salary') ? 'active' : '' }}">
            <i class="bi bi-award-fill"></i>
            <span>Salary</span>
        </a>
        <a href="{{ route('dashboard.team') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.team') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Team</span>
        </a>
        <a href="{{ route('dashboard.history') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.history') ? 'active' : '' }}">
            <i class="bi bi-wallet2"></i>
            <span>Wallet</span>
        </a>
        <a href="{{ route('dashboard.profile') }}" class="mobile-nav-item {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i>
            <span>Profile</span>
        </a>
    </nav>
    @else
    <!-- Guest Layout Wrapper -->
    <div class="guest-wrapper">
        @yield('content')
    </div>
    @endauth

    <!-- Help Center Modal (Preserved 100%) -->
    <div class="modal fade" id="helpCenterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-bottom py-3 px-4" style="background: var(--fg-forest); color: #fff;">
                    <h5 class="modal-title fw-bold font-heading d-flex align-items-center gap-2">
                        <i class="bi bi-headset text-warning"></i> FutureGrowth Support Center
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="nav nav-pills nav-fill mb-4 p-1 rounded-pill bg-light" id="helpTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active rounded-pill fw-bold" id="ticket-tab" data-bs-toggle="tab" data-bs-target="#ticket" type="button"><i class="bi bi-chat-dots-fill me-1"></i> Submit Ticket</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link rounded-pill fw-bold" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq" type="button"><i class="bi bi-question-circle-fill me-1"></i> Quick FAQ</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="helpTabContent">
                        <!-- Ticket Tab -->
                        <div class="tab-pane fade show active" id="ticket" role="tabpanel">
                            @auth
                            <form action="{{ route('dashboard.tickets.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Subject</label>
                                    <select name="subject" class="form-select rounded-3" required>
                                        <option value="Deposit Issue">Deposit Issue</option>
                                        <option value="Withdrawal Issue">Withdrawal Issue</option>
                                        <option value="Investment / ROI Issue">Investment / ROI Issue</option>
                                        <option value="Referral / Team Issue">Referral / Team Issue</option>
                                        <option value="Leadership Salary Issue">Leadership Salary Issue</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Message</label>
                                    <textarea name="message" rows="3" class="form-control rounded-3" required placeholder="Describe your question or issue..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-muted small text-uppercase fw-bold">Screenshot Attachment (Optional)</label>
                                    <input type="file" name="screenshot" class="form-control rounded-3" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-fg-primary w-100 fw-bold">Submit Support Ticket</button>
                            </form>
                            
                            <hr class="my-4">
                            <h6 class="fw-bold mb-3"><i class="bi bi-clock-history text-info me-2"></i> Recent Tickets</h6>
                            @php
                                $userTickets = \App\Models\SupportTicket::where('user_id', auth()->id())->latest()->take(5)->get();
                            @endphp
                            <div class="list-group list-group-flush rounded-3">
                                @forelse($userTickets as $t)
                                    <div class="list-group-item border rounded-3 mb-2 p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold small">{{ $t->subject }}</span>
                                            @if($t->status === 'open')
                                                <span class="badge bg-warning text-dark rounded-pill">Open</span>
                                            @elseif($t->status === 'answered')
                                                <span class="badge bg-success rounded-pill">Answered</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">Closed</span>
                                            @endif
                                        </div>
                                        <p class="small text-muted mb-1"><strong>You:</strong> {{ $t->message }}</p>
                                        @if($t->reply)
                                            <div class="p-2 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 small mt-2">
                                                <strong class="text-success">Support:</strong> {{ $t->reply }}
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center text-muted small py-3">No support tickets found.</div>
                                @endforelse
                            </div>
                            @else
                            <div class="alert alert-warning border-0 text-center">
                                Please <a href="{{ route('login') }}" class="alert-link fw-bold">login</a> to submit support tickets.
                            </div>
                            @endauth
                        </div>

                        <!-- FAQ Tab -->
                        <div class="tab-pane fade" id="faq" role="tabpanel">
                            <div class="accordion accordion-flush" id="faqAccordion">
                                <div class="accordion-item border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                            How does the 300% (3X) Return Cap work?
                                        </button>
                                    </h2>
                                    <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted small">Your active investments generate daily ROI up to a strict 300% (3X) total earning multiplier. Once your investment reaches 3X the invested principal, it automatically marks as completed.</div>
                                    </div>
                                </div>
                                <div class="accordion-item border-bottom">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                            How do Referral Rewards and Monthly Salary work?
                                        </button>
                                    </h2>
                                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted small">You receive an instant 20% Direct Reward on investments from direct members, plus multi-level commissions across 10 upline tiers. Qualifying direct members with active investments of $50+ also unlock Monthly Leadership Salaries up to $300/month.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                            When can I withdraw?
                                        </button>
                                    </h2>
                                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body text-muted small">Withdrawals can be requested anytime your balance exceeds the minimum withdrawal amount of ${{ setting('min_withdrawal', 10) }}. A standard 5% platform fee applies.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(setting('whatsapp_button_enabled', '1') && setting('whatsapp_community_link'))
                    <div class="mt-4 pt-3 border-top text-center">
                        <a href="{{ setting('whatsapp_community_link') }}" target="_blank" class="btn btn-fg-primary rounded-pill px-4 fw-bold w-100">
                            <i class="bi bi-whatsapp me-2"></i> Chat with Official WhatsApp Community
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Core Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        AOS.init({ duration: 700, once: true });

        function toggleSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');
        }

        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2-all"></i> Copied!';
                btn.classList.add('btn-success');
                setTimeout(() => {
                    btn.innerHTML = originalHtml;
                    btn.classList.remove('btn-success');
                }, 2500);
            });
        }
    </script>
    @stack('scripts')
</body>
</html>
