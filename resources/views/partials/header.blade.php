<header class="header_area sticky-top w-100"
    style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); z-index: 1030;">
    <style>
        /* Header Enhanced Styling */
        .navbar {
            padding: 0.8rem 0;
        }

        .nav-link {
            position: relative;
            color: #2c3e50 !important;
            font-weight: 600;
            font-size: 15px;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            transition: color 0.3s ease;
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
            bottom: 0px;
            left: 50%;
            background-color: #BC002D;
            transition: all 0.3s ease;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .nav-link:hover::after,
        .nav-item.active .nav-link::after {
            width: 80%;
        }

        /* Dropdown Enhancements */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 0.5rem;
            animation: fadeInDropdown 0.2s ease-out forwards;
        }

        @keyframes fadeInDropdown {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            border-radius: 8px;
            transition: all 0.2s ease;
            font-weight: 500;
            padding: 0.6rem 1rem;
            color: #4a5568;
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

        /* Action Buttons */
        .btn-action-icon {
            color: #2c3e50;
            transition: all 0.2s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: transparent;
        }

        .btn-action-icon:hover {
            background: rgba(188, 0, 45, 0.08);
            color: #BC002D;
        }

        /* User Avatar */
        .user-avatar {
            width: 38px;
            height: 38px;
            font-size: 14px;
            background: linear-gradient(135deg, #BC002D, #e63946);
            box-shadow: 0 4px 10px rgba(188, 0, 45, 0.2);
            transition: transform 0.2s ease;
        }

        .dropdown-toggle-user:hover .user-avatar {
            transform: scale(1.05);
        }

        /* Login Pill Button */
        .btn-login {
            background: #BC002D;
            color: white !important;
            border-radius: 50px;
            padding: 0.4rem 1.2rem;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(188, 0, 45, 0.25);
            border: none;
            margin-left: 1rem;
        }

        .btn-login:hover {
            background: #9a0025;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(188, 0, 45, 0.35);
        }

        /* Toggler Icon for Mobile */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .navbar-toggler:focus,
        .navbar-toggler:hover {
            background: rgba(0, 0, 0, 0.05) !important;
            box-shadow: none;
        }

        .hover-scale {
            transition: transform 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.03);
        }

        .text-japan-red {
            color: #BC002D !important;
        }
    </style>

    <div class="main_menu">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid px-3 px-lg-5">
                <!-- Mobile Sidebar Toggle (Dashboard Only) -->
                @if(request()->is('dashboard*'))
                    <button class="btn btn-link d-lg-none me-2 p-0 text-dark" type="button" onclick="toggleSidebar()">
                        <span class="material-symbols-outlined fs-2">menu</span>
                    </button>
                @endif

                <a class="navbar-brand py-0 d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo_1.png') }}" alt="Waku Trip" style="height: 35px; object-fit: contain;"
                        class="brand-logo hover-scale">
                </a>

                {{-- Mobile Actions: Currency + Avatar (Visible only on mobile <lg) --}} <div
                    class="d-flex align-items-center gap-1 d-lg-none ms-auto me-2">
                    <!-- Currency Switcher Mobile -->
                    <div class="dropdown">
                        <button class="btn btn-action-icon shadow-none" type="button" id="currencyDropdownMobile"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-symbols-outlined fs-5">payments</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="currencyDropdownMobile"
                            style="min-width: 160px; margin-top: 10px;">
                            @foreach(config('currency.currencies') as $code => $details)
                                <li>
                                    <form action="{{ route('currency.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="currency" value="{{ $code }}">
                                        <button type="submit"
                                            class="dropdown-item d-flex justify-content-between align-items-center py-2 {{ ($currentCurrency ?? 'USD') == $code ? 'active' : '' }}">
                                            <span class="fw-bold">{{ $code }}</span>
                                            <span
                                                class="small {{ ($currentCurrency ?? 'USD') == $code ? 'text-white-50' : 'text-muted' }}">{{ $details['symbol'] }}</span>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @auth
                        <div class="dropdown">
                            <button class="btn p-0 dropdown-toggle-user ms-1 shadow-none" type="button"
                                id="userDropdownMobile" data-bs-toggle="dropdown" aria-expanded="false"
                                style="border: none; background: none;">
                                <div
                                    class="rounded-circle user-avatar d-flex align-items-center justify-content-center text-white fw-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdownMobile"
                                style="min-width: 200px; margin-top: 10px;">
                                <div class="px-3 py-3 border-bottom bg-light rounded-top">
                                    <p class="mb-0 fw-bold text-dark">{{ Auth::user()->name }}</p>
                                </div>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 mt-2"
                                    href="{{ route('dashboard') }}">
                                    <span class="material-symbols-outlined text-japan-red"
                                        style="font-size: 20px;">space_dashboard</span>
                                    <span>Dashboard</span>
                                </a>
                                <div class="dropdown-divider my-2"></div>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                        <span class="material-symbols-outlined" style="font-size: 20px;">logout</span>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
            </div>

            <button class="navbar-toggler shadow-none bg-transparent" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="material-symbols-outlined fs-1 text-dark">menu</span>
            </button>

            <div class="collapse navbar-collapse mt-3 mt-lg-0" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto text-center text-lg-start mt-2 mt-lg-0">
                    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item {{ request()->is('planned_list') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ url('/planned_list') }}">Planned Trip</a></li>
                    <li class="nav-item {{ request()->is('private_list') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ url('/private_list') }}">Private Trip</a></li>
                    <li class="nav-item dropdown {{ request()->is('news*') ? 'active' : '' }}">
                        <a href="#"
                            class="nav-link dropdown-toggle d-flex align-items-center justify-content-center justify-content-lg-start gap-1"
                            data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            News
                        </a>
                        <ul class="dropdown-menu shadow-sm mt-lg-3">
                            <li><a class="dropdown-item" href="{{ url('/news') }}">Trending</a></li>
                            <li><a class="dropdown-item" href="{{ url('/news') }}">Recent</a></li>
                        </ul>
                    </li>
                    <li class="nav-item {{ request()->is('aboutus') ? 'active' : '' }}"><a class="nav-link"
                            href="{{ url('/aboutus') }}">About Us</a></li>
                </ul>

                <div
                    class="nav-right d-flex flex-column flex-lg-row align-items-center gap-2 mt-3 mt-lg-0 pb-3 pb-lg-0 border-top border-lg-0 pt-3 pt-lg-0">
                    <!-- Currency Switcher Desktop -->
                    <div class="dropdown d-none d-lg-block">
                        <button class="btn btn-action-icon gap-1 px-3 w-auto shadow-none d-flex align-items-center"
                            type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-symbols-outlined" style="font-size: 20px;">payments</span>
                            <span class="fw-bold small">{{ $currentCurrency ?? 'USD' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-3 shadow-sm" aria-labelledby="currencyDropdown"
                            style="min-width: 200px;">
                            @foreach(config('currency.currencies') as $code => $details)
                                <li>
                                    <form action="{{ route('currency.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="currency" value="{{ $code }}">
                                        <button type="submit"
                                            class="dropdown-item d-flex justify-content-between align-items-center py-2 {{ ($currentCurrency ?? 'USD') == $code ? 'active' : '' }}">
                                            <span class="d-flex align-items-center gap-2">
                                                <span class="fw-bold">{{ $code }}</span>
                                                <span
                                                    class="{{ ($currentCurrency ?? 'USD') == $code ? 'text-white-50' : 'text-muted' }} small">{{ $details['name'] }}</span>
                                            </span>
                                            <span
                                                class="{{ ($currentCurrency ?? 'USD') == $code ? 'text-white' : 'text-dark' }} fw-semibold">{{ $details['symbol'] }}</span>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @auth
                        {{-- Desktop User Dropdown --}}
                        <div class="dropdown d-none d-lg-block">
                            <button
                                class="btn shadow-none dropdown-toggle-user d-flex align-items-center gap-2 p-1 pe-3 rounded-pill bg-light border"
                                type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                style="transition: all 0.2s;">
                                <div
                                    class="rounded-circle user-avatar d-flex align-items-center justify-content-center text-white fw-bold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="text-dark fw-bold small">{{ Auth::user()->name }}</span>
                                <span class="material-symbols-outlined text-secondary"
                                    style="font-size: 18px;">expand_more</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end mt-3 shadow-sm" aria-labelledby="userDropdown"
                                style="min-width: 220px;">
                                <div class="px-4 py-3 border-bottom bg-light rounded-top text-center">
                                    <div class="rounded-circle user-avatar mx-auto mb-2 d-flex align-items-center justify-content-center text-white fw-bold fs-5"
                                        style="width: 48px; height: 48px;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <p class="mb-0 fw-bold text-dark">{{ Auth::user()->name }}</p>
                                </div>
                                <a class="dropdown-item d-flex align-items-center gap-3 py-2 mt-2 px-4"
                                    href="{{ route('dashboard') }}">
                                    <span class="material-symbols-outlined text-japan-red">space_dashboard</span>
                                    <span class="fw-medium">Dashboard</span>
                                </a>
                                <div class="dropdown-divider my-2"></div>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item d-flex align-items-center gap-3 py-2 text-danger px-4">
                                        <span class="material-symbols-outlined">logout</span>
                                        <span class="fw-medium">Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Login Button (Responsive) -->
                        <a class="btn btn-login d-none d-lg-inline-block" href="{{ url('/login') }}">Sign In</a>
                        <!-- Mobile login button -->
                        <a class="btn btn-login d-lg-none mt-2 w-100 mx-0" href="{{ url('/login') }}">Sign In</a>
                    @endauth
                </div>
            </div>
    </div>
    </nav>
    </div>
</header>