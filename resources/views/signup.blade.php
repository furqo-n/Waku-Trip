<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body class="position-relative overflow-x-hidden"
    style="font-family: 'Inter', sans-serif; background: linear-gradient(rgba(248, 249, 250, 0.8), rgba(248, 249, 250, 0.9)), url('{{ app_setting('register_bg', 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?q=80&w=1994&auto=format&fit=crop') }}') center/cover no-repeat fixed;">

    <!-- Background Decorative Circle -->
    <div class="position-absolute rounded-circle bg-japan-red opacity-10"
        style="width: 600px; height: 600px; top: -100px; right: -150px; z-index: 0; filter: blur(80px);">
    </div>

    <!-- Minimal Header -->
    <header class="container-fluid py-3 position-relative" style="z-index: 2;">
        <div class="d-flex justify-content-between align-items-center px-md-4">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo_1.png') }}" alt="Waku Trip" style="height: 40px;">
            </a>
            <a href="#" class="text-dark text-decoration-none fw-bold small">Help Center</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container py-5 position-relative" style="z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">

                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">

                    <div class="text-center mb-4">
                        <h1 class="fw-bold fs-2 mb-2 text-dark">Join the Waku Community</h1>
                        <p class="text-secondary small">Start your journey into the heart of Japan.</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small mb-1">Full Name</label>
                            <input type="text" name="name"
                                class="form-control form-control-lg rounded-3 border bg-light fs-6 @error('name') is-invalid @enderror"
                                placeholder="e.g., Kenji Tanaka" style="border-color: #e2e8f0;"
                                value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small mb-1">Email Address</label>
                            <input type="email" name="email"
                                class="form-control form-control-lg rounded-3 border bg-light fs-6 @error('email') is-invalid @enderror"
                                placeholder="your@email.com" style="border-color: #e2e8f0;" value="{{ old('email') }}"
                                required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark small mb-1">Password</label>
                            <div class="position-relative">
                                <input type="password" name="password" id="passwordInput"
                                    class="form-control form-control-lg rounded-3 border bg-light fs-6 pe-5 @error('password') is-invalid @enderror"
                                    placeholder="Create a strong password" style="border-color: #e2e8f0;" required>
                                <button type="button"
                                    class="btn position-absolute top-50 end-0 translate-middle-y me-2 border-0 p-1 bg-transparent"
                                    onclick="togglePassword('passwordInput', 'toggleIcon1')">
                                    <span class="material-symbols-outlined text-secondary fs-5"
                                        id="toggleIcon1">visibility</span>
                                </button>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control form-control-lg rounded-3 border bg-light fs-6"
                                placeholder="Repeat your password" style="border-color: #e2e8f0;" required>
                        </div>

                        <!-- Newsletter Checkbox -->
                        <div class="form-check mb-4">
                            <input class="form-check-input mt-1" type="checkbox" id="newsletter">
                            <label class="form-check-label lh-sm" for="newsletter">
                                <span class="d-block fw-bold text-dark small">Subscribe to our Japan Travel
                                    Newsletter</span>
                                <span class="d-block text-secondary" style="font-size: 11px;">Get weekly updates on
                                    hidden gems and travel tips.</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="btn btn-japan w-100 py-3 rounded-pill fw-bold fs-5 shadow hover-scale mb-4">
                            Create Account
                        </button>

                        <!-- Divider -->
                        <div class="position-relative mb-4 text-center">
                            <hr class="opacity-25 my-3">
                            <span
                                class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-secondary text-uppercase fw-bold"
                                style="font-size: 10px; letter-spacing: 0.1em;">
                                Or sign up with
                            </span>
                        </div>

                        <!-- Social Buttons -->
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <button type="button"
                                    class="btn btn-outline-secondary w-100 py-3 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 border-2"
                                    style="background: #fff;">
                                    <svg width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="#4285F4"
                                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                        <path fill="#34A853"
                                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                        <path fill="#FBBC05"
                                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                        <path fill="#EA4335"
                                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                                    </svg>
                                    Google
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button"
                                    class="btn btn-outline-secondary w-100 py-3 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 border-2"
                                    style="background: #fff;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                        <path
                                            d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z" />
                                    </svg>
                                    Apple
                                </button>
                            </div>
                        </div>

                        <!-- Login Link -->
                        <p class="text-center text-secondary small mb-0">
                            Already have an account?
                            <a href="{{ url('/login') }}" class="text-japan-red text-decoration-none fw-bold">Log In</a>
                        </p>

                    </form>
                </div>

                <!-- Footer Text -->
                <div class="text-center mt-4">
                    <p class="text-secondary opacity-50 small mb-4" style="font-size: 10px; letter-spacing: 0.05em;">
                        BY JOINING, YOU AGREE TO OUR <a href="#" class="text-secondary text-decoration-underline">TERMS
                            OF SERVICE</a> AND <a href="#" class="text-secondary text-decoration-underline">PRIVACY
                            POLICY</a>.
                    </p>
                    <p class="text-secondary opacity-50 small" style="font-size: 11px;">
                        © 2026 Waku Trip. Handcrafted travel experiences for Japan.
                    </p>
                </div>

            </div>
        </div>
    </main>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                icon.textContent = 'visibility';
            }
        }
    </script>

</body>

</html>