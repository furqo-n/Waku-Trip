<header class="header_area container-fluid">
    <style>
        .dropdown-item:active,
        .dropdown-item.active {
            background-color: #BC002D !important;
            color: white !important;
        }

        .dropdown-item:hover {
            color: #BC002D !important;
        }
    </style>
    <div class="main_menu">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <!-- Mobile Sidebar Toggle (Dashboard Only) -->
                @if(request()->is('dashboard*'))
                    <button class="btn btn-link d-md-none me-2 p-0" type="button" onclick="toggleSidebar()"
                        style="font-size: 24px;">
                        <span class="material-icons">menu</span>
                    </button>
                @endif

                <a class="navbar-brand py-1" href="{{  url('/') }}">
                    <img src="{{ asset('img/logo_1.png') }}" alt="Waku Trip" style="height: 30px;">
                </a>

                {{-- Mobile Actions: Currency + Avatar (Visible only on mobile <lg) --}} <div
                    class="d-flex align-items-center gap-2 d-lg-none ms-auto me-2">
                    <!-- Currency Switcher Mobile -->
                    <div class="dropdown">
                        <button
                            class="btn btn-link text-decoration-none text-dark p-0 shadow-none d-flex align-items-center"
                            type="button" id="currencyDropdownMobile" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-symbols-outlined" style="font-size: 22px;">payments</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2"
                            aria-labelledby="currencyDropdownMobile" style="min-width: 150px;">
                            @foreach(config('currency.currencies') as $code => $details)
                                <li>
                                    <form action="{{ route('currency.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="currency" value="{{ $code }}">
                                        <button type="submit"
                                            class="dropdown-item d-flex justify-content-between align-items-center py-2 {{ ($currentCurrency ?? 'USD') == $code ? 'active' : '' }}">
                                            <span class="small fw-bold">{{ $code }}</span>
                                            <span class="text-muted smallest">{{ $details['symbol'] }}</span>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @auth
                        <div class="dropdown">
                            <button class="btn btn-link d-flex align-items-center gap-1 text-decoration-none p-0"
                                type="button" id="userDropdownMobile" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" style="border: none; background: none;">
                                <div class="rounded-circle bg-japan-red d-flex align-items-center justify-content-center text-white fw-bold"
                                    style="width: 32px; height: 32px; font-size: 12px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2"
                                aria-labelledby="userDropdownMobile" style="min-width: 180px;">
                                <div class="px-3 py-2 border-bottom">
                                    <p class="mb-0 fw-bold text-dark small">{{ Auth::user()->name }}</p>
                                </div>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                    href="{{ route('dashboard') }}">
                                    <span class="material-symbols-outlined text-japan-red"
                                        style="font-size: 18px;">dashboard</span>
                                    <span class="small">Dashboard</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger border-0 bg-transparent w-100 text-start">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                                        <span class="small">Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
            </div>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                <ul class="nav navbar-nav menu_nav justify-content-end">
                    <li class="nav-item active"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/planned_list') }}">Planned Trip</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/private_list') }}">Private Trip</a>
                    </li>
                    <li class="nav-item submenu dropdown">
                        <a href="{{ url('/news') }}" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                            role="button" aria-haspopup="true" aria-expanded="false">News</a>
                        <ul class="dropdown-menu">
                            <li class="nav-item"><a class="nav-link" href="{{ url('/news') }}">Trending</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/news') }}">Recent</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/aboutus') }}">About Us</a></li>
                </ul>

                <div
                    class="nav-right text-center text-lg-end py-1 py-lg-0 d-flex flex-column flex-lg-row align-items-center gap-1 ps-lg-2">
                    <!-- Currency Switcher Desktop -->
                    <div class="dropdown d-none d-lg-inline-block">
                        <button
                            class="btn btn-link text-decoration-none dropdown-toggle text-dark fw-bold p-0 shadow-none d-flex align-items-center"
                            type="button" id="currencyDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-symbols-outlined align-middle"
                                style="font-size: 20px;">payments</span>
                            <span class="align-middle ms-1">{{ $currentCurrency ?? 'USD' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2"
                            aria-labelledby="currencyDropdown">
                            @foreach(config('currency.currencies') as $code => $details)
                                <li>
                                    <form action="{{ route('currency.switch') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="currency" value="{{ $code }}">
                                        <button type="submit"
                                            class="dropdown-item d-flex justify-content-between align-items-center py-2 {{ ($currentCurrency ?? 'USD') == $code ? 'active' : '' }}">
                                            <span>{{ $code }} - {{ $details['name'] }}</span>
                                            <span class="text-muted small ms-3">{{ $details['symbol'] }}</span>
                                        </button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @auth
                        {{-- Desktop User Dropdown --}}
                        <div class="dropdown d-none d-lg-inline-block">
                            <button class="btn btn-link d-flex align-items-center gap-2 text-decoration-none p-0"
                                type="button" id="userDropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" style="border: none; background: none;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-japan-red d-flex align-items-center justify-content-center text-white fw-bold"
                                        style="width: 36px; height: 36px; font-size: 14px;">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <span class="text-dark fw-bold">{{ Auth::user()->name }}</span>
                                    <span class="material-symbols-outlined text-secondary"
                                        style="font-size: 18px;">expand_more</span>
                                </div>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2"
                                aria-labelledby="userDropdown" style="min-width: 180px;">
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                    href="{{ route('dashboard') }}">
                                    <span class="material-symbols-outlined text-japan-red"
                                        style="font-size: 18px;">dashboard</span>
                                    <span>Dashboard</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger border-0 bg-transparent w-100 text-start">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">logout</span>
                                        <span>Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Login Button (Responsive) -->
                        <a class="button text-center" href="{{ url('/login') }}">LogIn</a>
                    @endauth
                </div>
            </div>
    </div>
    </nav>
    </div>
</header>