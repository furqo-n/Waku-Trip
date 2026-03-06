<aside class="sidebar d-flex flex-column" id="sidebar">

    <!-- Navigation Links -->
    <nav class="px-3 py-4 d-flex flex-column gap-1 mt-3 flex-grow-1">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <span class="material-icons fs-5">dashboard</span>
            Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('mybooking') ? 'active' : '' }}" href="{{ route('mybooking') }}">
            <span class="material-icons fs-5">airplane_ticket</span>
            My Bookings
        </a>
        <a class="nav-link {{ request()->routeIs('rewards') ? 'active' : '' }}" href="{{ route('rewards') }}">
            <span class="material-icons fs-5">loyalty</span>
            Rewards
        </a>
        <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
            <span class="material-icons fs-5">settings</span>
            Settings
        </a>
    </nav>

    <!-- User Profile Minimal Sidebar Footer -->
    <div class="p-3 border-top" style="border-color: var(--neutral-light) !important;">
        <div class="d-flex align-items-center gap-3 w-100 p-2 rounded-3">
            <div class="rounded-circle bg-primary-custom d-flex align-items-center justify-content-center text-white fw-bold"
                style="width: 40px; height: 40px;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="text-start lh-1 flex-grow-1">
                <p class="mb-1 fw-semibold small text-dark">{{ Auth::user()->name }}</p>
                <p class="mb-0 text-secondary" style="font-size: 11px;">Traveler</p>
            </div>
            <div class="dropdown">
                <button class="btn btn-link text-secondary p-0" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <span class="material-icons fs-6">more_vert</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile') }}">Profile Settings</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">Sign Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>