<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<head>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="dashboard-page">

    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->

    <!-- Sidebar Navigation -->
    @include('partials.sidebar')

    <!-- Main Content Area -->
    <main class="main-content p-4 p-lg-5">

        <script>
            function toggleSidebar() {
                document.getElementById('sidebar').classList.toggle('show');
            }
        </script>

        <!-- Page Header -->
        <div class="mb-5">
            <h1 class="fw-bold mb-2" style="font-size: 32px;">Profile Settings</h1>
            <p class="text-secondary">Manage your account details and travel preferences</p>
        </div>

        <!-- Profile Photo Section -->
        <section class="dashboard-card mb-4">
            <div class="row align-items-end">
                <div class="col-auto text-center">
                    <div class="position-relative d-inline-block">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB-FMEiKO32KZn8kLwHa3StuUIDKWkxJMPXIeF7tIXS_6W_5yKFJKCZWh7eqJ4J9Slrm1e6eVfMXLGA2jgnsBhVJqk8vP6Ilv27tmtZ_5_AkXcXekbv0S6hgjY134uHkuDe-ly1_byx_AIBo-wDi89Jvmbi31i4isUtQv1CTVqxyTYZebPzpOzDuT2GBK3Nm_kdQtx-8Xtj7iv6vs35X1pPyS0k5YyAgAVFqj9-sn80jCVaSjUmac04o9cbWWw-j19fCegma1IjYak"
                            alt="Profile" class="rounded-circle object-fit-cover border border-3"
                            style="width: 120px; height: 120px; border-color: var(--neutral-light) !important;">
                        <button class="btn btn-warning-custom position-absolute bottom-0 end-0 rounded-circle p-2"
                            style="width: 36px; height: 36px;">
                            <i class="material-icons" style="font-size: 18px; line-height: 1;">edit</i>
                        </button>
                    </div>
                </div>
                <div class="col">
                    <h2 class="h4 fw-bold mb-1">{{ Auth::user()->name }}</h2>
                    <p class="text-secondary small mb-3">Tokyo based traveler since 2021</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm">Change Photo</button>
                        <button class="btn btn-link btn-sm text-danger">Remove</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Personal Information -->
        <section class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <i class="material-icons text-primary-custom">person</i>
                <h3 class="h5 fw-bold mb-0">Personal Information</h3>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-uppercase fw-semibold small text-secondary">Full Name</label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" placeholder="Your name">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-uppercase fw-semibold small text-secondary">Email Address</label>
                    <input type="email" class="form-control" value="{{ Auth::user()->email }}"
                        placeholder="email@example.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-uppercase fw-semibold small text-secondary">Phone Number</label>
                    <input type="tel" class="form-control" value="+81 90-1234-5678" placeholder="+81 00-0000-0000">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-uppercase fw-semibold small text-secondary">Language
                        Preference</label>
                    <select class="form-select">
                        <option selected>English</option>
                        <option>Japanese (日本語)</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Travel Preferences -->
        <section class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <i class="material-icons text-primary-custom">explore</i>
                <h3 class="h5 fw-bold mb-0">Travel Preferences</h3>
            </div>
            <div class="mb-4">
                <label class="form-label text-uppercase fw-semibold small text-secondary mb-3">Interests</label>
                <div class="d-flex flex-wrap gap-2">
                    <div class="form-check">
                        <input class="btn-check" type="checkbox" id="interest1" checked>
                        <label class="btn btn-outline-primary rounded-pill btn-sm" for="interest1">
                            🍜 Foodie
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="btn-check" type="checkbox" id="interest2" checked>
                        <label class="btn btn-outline-primary rounded-pill btn-sm" for="interest2">
                            🗻 Nature
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="btn-check" type="checkbox" id="interest3" checked>
                        <label class="btn btn-outline-primary rounded-pill btn-sm" for="interest3">
                            🏯 History
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="btn-check" type="checkbox" id="interest4">
                        <label class="btn btn-outline-primary rounded-pill btn-sm" for="interest4">
                            🏙️ Architecture
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="btn-check" type="checkbox" id="interest5">
                        <label class="btn btn-outline-primary rounded-pill btn-sm" for="interest5">
                            🛍️ Shopping
                        </label>
                    </div>
                </div>
            </div>
            <div>
                <label class="form-label text-uppercase fw-semibold small text-secondary">Dietary Restrictions</label>
                <textarea class="form-control" rows="3"
                    placeholder="e.g. Vegetarian, No seafood, Nut allergies..."></textarea>
            </div>
        </section>

        <!-- Account Security -->
        <section class="dashboard-card mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <i class="material-icons text-primary-custom">security</i>
                <h3 class="h5 fw-bold mb-0">Account Security</h3>
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label text-uppercase fw-semibold small text-secondary">Current Password</label>
                    <input type="password" class="form-control" value="••••••••" placeholder="Enter current password">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-uppercase fw-semibold small text-secondary">New Password</label>
                    <input type="password" class="form-control" placeholder="Min. 8 characters">
                </div>
            </div>
        </section>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-end gap-3 py-4">
            <button class="btn btn-outline-secondary px-4">Discard Changes</button>
            <button class="btn btn-primary-custom px-5">Save All Changes</button>
        </div>

    </main>

    @include('partials.script')

</body>

</html>