<header class="header_area sticky-top w-100"
    style="background: rgba(255,255,255,0.97); backdrop-filter: blur(12px); box-shadow: 0 2px 16px rgba(0,0,0,0.06); z-index: 1030;">
    <style>
        /* ── Base Navbar ─────────────────────────────────── */
        .header_area .navbar {
            padding: 0;
            min-height: 58px;
        }

        .header_area .container-fluid {
            height: 58px;
            display: flex;
            align-items: center;
        }

        /* ── Logo ────────────────────────────────────────── */
        .brand-logo {
            height: 34px;
            width: auto;
            object-fit: contain;
            display: block;
            transition: opacity 0.2s ease;
        }

        .navbar-brand {
            padding: 0;
            margin-right: 0;
            line-height: 1;
        }

        .navbar-brand:hover .brand-logo {
            opacity: 0.85;
        }

        /* ── Nav Links ───────────────────────────────────── */
        .nav-link {
            position: relative;
            color: #2c3e50 !important;
            font-weight: 600;
            font-size: 14.5px;
            padding: 0.45rem 0.9rem !important;
            margin: 0 0.1rem;
            transition: color 0.25s ease;
            white-space: nowrap;
        }

        .nav-link:hover,
        .nav-item.active .nav-link,
        .nav-link:focus {
            color: #BC002D !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 50%;
            background-color: #BC002D;
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-link:hover::after,
        .nav-item.active .nav-link::after {
            width: 70%;
        }

        /* ── Dropdown ────────────────────────────────────── */
        .dropdown-menu {
            border: none;
            background-color: #f5ececff;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.79);
            border-radius: 12px;
            padding: 0.4rem;
            animation: fadeInDrop 0.18s ease-out forwards;
        }

        @keyframes fadeInDrop {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            border-radius: 8px;
            transition: all 0.18s ease;
            font-weight: 500;
            padding: 0.55rem 1rem;
            color: #4a5568;
            font-size: 14px;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background-color: #fff1f2 !important;
            color: #BC002D !important;
        }

        .dropdown-item.active {
            background-color: #BC002D !important;
            color: white !important;
        }

        /* ── Icon Buttons ────────────────────────────────── */
        .btn-icon-hdr {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            background: transparent;
            color: #374151;
            transition: background 0.2s ease, color 0.2s ease;
            padding: 0;
            flex-shrink: 0;
        }

        .btn-icon-hdr:hover {
            background: rgba(188, 0, 45, 0.08);
            color: #BC002D;
        }

        /* ── User Avatar ─────────────────────────────────── */
        .user-avatar-hdr {
            width: 34px;
            height: 34px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 50%;
            background: linear-gradient(135deg, #BC002D, #e63946);
            box-shadow: 0 3px 8px rgba(188, 0, 45, 0.25);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .dropdown-toggle-user:hover .user-avatar-hdr {
            transform: scale(1.06);
        }

        /* ── Login Button ────────────────────────────────── */
        .btn-login-hdr {
            background: #BC002D;
            color: white !important;
            border-radius: 50px;
            padding: 0.35rem 1.1rem;
            font-weight: 600;
            font-size: 13.5px;
            border: none;
            box-shadow: 0 3px 12px rgba(188, 0, 45, 0.22);
            transition: all 0.25s ease;
            white-space: nowrap;
        }

        .btn-login-hdr:hover {
            background: #9a0025;
            transform: translateY(-1px);
            box-shadow: 0 5px 18px rgba(188, 0, 45, 0.32);
        }

        /* ── Mobile Hamburger ───────────────────────────── */
        .hdr-toggler {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            border-radius: 8px;
            padding: 0;
            color: #374151;
            transition: background 0.2s ease;
            flex-shrink: 0;
            cursor: pointer;
        }

        .hdr-toggler:hover,
        .hdr-toggler:focus {
            background: rgba(0, 0, 0, 0.05);
            outline: none;
            box-shadow: none;
        }

        /* ── Mobile Collapse ─────────────────────────────── */
        @media (max-width: 991.98px) {
            .header_area .navbar {
                min-height: 56px;
            }

            .header_area .container-fluid {
                height: 56px;
                flex-wrap: wrap;
                padding-left: 14px;
                padding-right: 14px;
            }

            /* The top bar stays at 56px, collapse drops below */
            #navbarSupportedContent {
                width: 100%;
                border-top: 1px solid rgba(0, 0, 0, 0.07);
                padding: 8px 0 12px;
            }

            .navbar-nav .nav-link {
                padding: 0.55rem 0.5rem !important;
                font-size: 14.5px;
                text-align: left;
                border-radius: 8px;
            }

            .navbar-nav .nav-link:hover {
                background: rgba(188, 0, 45, 0.05);
            }

            .navbar-nav .nav-link::after {
                display: none;
            }

            .mobile-nav-footer {
                padding-top: 8px;
                border-top: 1px solid rgba(0, 0, 0, 0.06);
                margin-top: 4px;
            }
        }

        /* ── Desktop only ────────────────────────────────── */
        .hover-scale {
            transition: transform 0.25s ease;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }

        .text-japan-red {
            color: #BC002D !important;
        }
    </style>

    <div class="main_menu">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid px-3 px-md-4 px-lg-5">

                {{-- ════ LEFT: Logo (+ sidebar toggle on dashboard) ════ --}}
                <div class="d-flex align-items-center" style="flex-shrink:0;">
                    @if(request()->is('dashboard*'))
                        <button class="btn-icon-hdr d-lg-none me-1" type="button" onclick="toggleSidebar()"
                            aria-label="Sidebar">
                            <span class="material-symbols-outlined" style="font-size:22px;">menu</span>
                        </button>
                    @endif

                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('img/logo_1.png') }}" alt="Waku Trip" class="brand-logo hover-scale">
                    </a>
                </div>

                {{-- ════ RIGHT (mobile only): currency + avatar + hamburger ════ --}}
                <div class="d-flex align-items-center gap-2 d-lg-none ms-auto">

                    {{-- Currency icon --}}
                    <div class="dropdown">
                        <button class="btn-icon-hdr" type="button" id="currMobile" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-label="Currency">
                            <span class="material-symbols-outlined" style="font-size:20px;">payments</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="currMobile"
                            style="min-width:150px; margin-top:8px;">
                            @foreach(config('currency.currencies') as $code => $details)
                                <li>
                                    <form action="{{ route('currency.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="currency" value="{{ $code }}">
                                        <button type="submit"
                                            class="dropdown-item d-flex justify-content-between align-items-center {{ ($currentCurrency ?? 'USD') == $code ? 'active' : '' }}">
                                            <span class="fw-semibold">{{ $code }}</span>
                                            <span
                                                class="small {{ ($currentCurrency ?? 'USD') == $code ? 'text-white-50' : 'text-muted' }}">{{ $details['symbol'] }}</span>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- User avatar / Login --}}
                    @auth
                        <div class="dropdown">
                            <button class="dropdown-toggle-user p-0" type="button" id="avatarMobile"
                                data-bs-toggle="dropdown" aria-expanded="false"
                                style="border:none; background:none; cursor:pointer;">
                                <div class="user-avatar-hdr">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="avatarMobile"
                                style="min-width:200px; margin-top:8px;">
                                <div class="px-3 py-2 border-bottom bg-light" style="border-radius:12px 12px 0 0;">
                                    <p class="mb-0 fw-bold text-dark" style="font-size:14px;">{{ Auth::user()->name }}</p>
                                </div>
                                <a class="dropdown-item d-flex align-items-center gap-2 mt-1"
                                    href="{{ route('dashboard') }}">
                                    <span class="material-symbols-outlined text-japan-red"
                                        style="font-size:18px;">space_dashboard</span>
                                    Dashboard
                                </a>
                                <div class="dropdown-divider my-1"></div>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                        <span class="material-symbols-outlined" style="font-size:18px;">logout</span>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a class="btn-login-hdr" href="{{ url('/login') }}">Sign In</a>
                    @endauth

                    {{-- Hamburger --}}
                    <button class="hdr-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Menu">
                        <span class="material-symbols-outlined" style="font-size:22px;">menu</span>
                    </button>
                </div>

                {{-- ════ COLLAPSE: nav links + desktop right actions ════ --}}
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-lg-auto">
                        <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item {{ request()->is('planned_list') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/planned_list') }}">Planned Trip</a>
                        </li>
                        <li class="nav-item {{ request()->is('private_list') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/private_list') }}">Private Trip</a>
                        </li>
                        <li class="nav-item dropdown {{ request()->is('news*') ? 'active' : '' }}">
                            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center gap-1"
                                data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                News
                            </a>
                            <ul class="dropdown-menu mt-lg-2">
                                <li><a class="dropdown-item" href="{{ url('/news') }}">Trending</a></li>
                                <li><a class="dropdown-item" href="{{ url('/news') }}">Recent</a></li>
                            </ul>
                        </li>
                        <li class="nav-item {{ request()->is('aboutus') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/aboutus') }}">About Us</a>
                        </li>
                    </ul>

                    {{-- Desktop right actions --}}
                    <div class="d-none d-lg-flex align-items-center gap-2 ms-2">
                        {{-- Currency Desktop --}}
                        <div class="dropdown">
                            <button class="btn-icon-hdr gap-1 px-3 w-auto rounded-pill d-flex align-items-center"
                                type="button" id="currDesktop" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined" style="font-size:18px;">payments</span>
                                <span class="fw-semibold" style="font-size:13px;">{{ $currentCurrency ?? 'USD' }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="currDesktop"
                                style="min-width:200px;">
                                @foreach(config('currency.currencies') as $code => $details)
                                    <li>
                                        <form action="{{ route('currency.switch') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="currency" value="{{ $code }}">
                                            <button type="submit"
                                                class="dropdown-item d-flex justify-content-between align-items-center {{ ($currentCurrency ?? 'USD') == $code ? 'active' : '' }}">
                                                <span class="d-flex align-items-center gap-2">
                                                    <span class="fw-semibold">{{ $code }}</span>
                                                    <span
                                                        class="small {{ ($currentCurrency ?? 'USD') == $code ? 'text-white-50' : 'text-muted' }}">{{ $details['name'] }}</span>
                                                </span>
                                                <span
                                                    class="fw-semibold {{ ($currentCurrency ?? 'USD') == $code ? 'text-white' : 'text-dark' }}">{{ $details['symbol'] }}</span>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @auth
                            {{-- Desktop User --}}
                            <div class="dropdown">
                                <button class="btn dropdown-toggle-user d-flex align-items-center gap-2 shadow-none"
                                    type="button" id="userDesktop" data-bs-toggle="dropdown" aria-expanded="false"
                                    style="background: #f8f8f8; border: 1px solid #e5e7eb; border-radius: 50px; padding: 5px 12px 5px 5px; transition: all 0.2s;">
                                    <div class="user-avatar-hdr">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                    <span class="text-dark fw-semibold"
                                        style="font-size:13.5px;">{{ Auth::user()->name }}</span>
                                    <span class="material-symbols-outlined text-secondary"
                                        style="font-size:16px;">expand_more</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="userDesktop"
                                    style="min-width:210px;">
                                    <div class="px-4 py-3 border-bottom text-center"
                                        style="background:#f9fafb; border-radius:12px 12px 0 0;">
                                        <div class="user-avatar-hdr mx-auto mb-2"
                                            style="width:44px; height:44px; font-size:16px;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                        <p class="mb-0 fw-bold text-dark" style="font-size:14px;">{{ Auth::user()->name }}
                                        </p>
                                    </div>
                                    <a class="dropdown-item d-flex align-items-center gap-3 mt-1 px-4"
                                        href="{{ route('dashboard') }}">
                                        <span class="material-symbols-outlined text-japan-red"
                                            style="font-size:19px;">space_dashboard</span>
                                        <span class="fw-medium">Dashboard</span>
                                    </a>
                                    <div class="dropdown-divider my-1"></div>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit"
                                            class="dropdown-item d-flex align-items-center gap-3 text-danger px-4">
                                            <span class="material-symbols-outlined" style="font-size:19px;">logout</span>
                                            <span class="fw-medium">Sign Out</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a class="btn-login-hdr" href="{{ url('/login') }}">Sign In</a>
                        @endauth
                    </div>

                    {{-- Mobile login (inside collapse, guest only) --}}
                    @guest
                        <div class="d-lg-none mobile-nav-footer">
                            <a class="btn-login-hdr d-block text-center" href="{{ url('/login') }}">Sign In</a>
                        </div>
                    @endguest
                </div>

            </div>
        </nav>
    </div>
</header>