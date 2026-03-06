<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body class="bg-white">

    <div class="container-fluid p-0 vh-100">
        <div class="row g-0 h-100">

            <!-- Left Side - Image Carousel -->
            <div class="col-lg-6 d-none d-lg-block position-relative">
                <div id="loginCarousel" class="carousel slide h-100" data-bs-ride="carousel">
                    <div class="carousel-inner h-100">
                        <div class="carousel-item active h-100">
                            <div class="position-relative h-100"
                                style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('{{ app_setting('login_bg_1', 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?q=80&w=1994&auto=format&fit=crop') }}') center/cover;">
                                <div class="position-absolute bottom-0 start-0 p-5 text-white"
                                    style="max-width: 600px;">
                                    <h1 class="display-3 fw-bold mb-4"
                                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Your Japanese journey starts here.
                                    </h1>
                                    <p class="fs-5 mb-0 text-white-50">
                                        Discover the hidden gems of Japan, from the neon lights of Shinjuku to the
                                        serene temples of Kyoto.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item h-100">
                            <div class="position-relative h-100"
                                style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('{{ app_setting('login_bg_2', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=2070&auto=format&fit=crop') }}') center/cover;">
                                <div class="position-absolute bottom-0 start-0 p-5 text-white"
                                    style="max-width: 600px;">
                                    <h1 class="display-3 fw-bold mb-4"
                                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Experience authentic Japan.
                                    </h1>
                                    <p class="fs-5 mb-0 text-white-50">
                                        Immerse yourself in centuries-old traditions and cutting-edge modernity.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item h-100">
                            <div class="position-relative h-100"
                                style="background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('{{ app_setting('login_bg_3', 'https://images.unsplash.com/photo-1590559902693-b71569424e66?q=80&w=2070&auto=format&fit=crop') }}') center/cover;">
                                <div class="position-absolute bottom-0 start-0 p-5 text-white"
                                    style="max-width: 600px;">
                                    <h1 class="display-3 fw-bold mb-4"
                                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        Waku waku adventures await.
                                    </h1>
                                    <p class="fs-5 mb-0 text-white-50">
                                        Create unforgettable memories in the land of the rising sun.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Indicators -->
                    <div class="carousel-indicators position-absolute bottom-0 start-0 mb-4 ms-5">
                        <button type="button" data-bs-target="#loginCarousel" data-bs-slide-to="0"
                            class="active bg-japan-red" style="width: 40px; height: 3px;"></button>
                        <button type="button" data-bs-target="#loginCarousel" data-bs-slide-to="1"
                            class="bg-white opacity-50" style="width: 40px; height: 3px;"></button>
                        <button type="button" data-bs-target="#loginCarousel" data-bs-slide-to="2"
                            class="bg-white opacity-50" style="width: 40px; height: 3px;"></button>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-lg-5">
                <div class="w-100" style="max-width: 480px;">

                    <!-- Logo -->
                    <div class="mb-5">
                        <a href="{{ url('/') }}" class="d-inline-block">
                            <img src="{{ asset('img/logo_1.png') }}" alt="Waku Trip" style="height: 50px;">
                        </a>
                    </div>

                    <!-- Header -->
                    <div class="mb-5">
                        <h1 class="fw-bold mb-2" style="font-size: 36px; color: #0f172a;">Welcome back</h1>
                        <p class="text-japan-red mb-0">Enter your details to plan your next adventure.</p>
                    </div>

                    <!-- Login Form -->
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2">Email Address</label>
                            <input type="email" name="email"
                                class="form-control form-control-lg rounded-4 border-2 @error('email') is-invalid @enderror"
                                placeholder="e.g. travel@waku.com"
                                style="padding: 1rem 1.25rem; background: #f8fafc; border-color: #e2e8f0;"
                                value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark mb-2">Password</label>
                            <div class="position-relative">
                                <input type="password" name="password" id="passwordInput"
                                    class="form-control form-control-lg rounded-4 border-2 pe-5 @error('password') is-invalid @enderror"
                                    placeholder="••••••••"
                                    style="padding: 1rem 1.25rem; background: #f8fafc; border-color: #e2e8f0;" required>
                                <button type="button"
                                    class="btn position-absolute top-50 end-0 translate-middle-y me-2 border-0 p-2"
                                    onclick="togglePassword()">
                                    <span class="material-symbols-outlined text-secondary"
                                        id="toggleIcon">visibility</span>
                                </button>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label text-secondary" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" class="text-japan-red text-decoration-none fw-bold small">Forgot Password?</a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="btn btn-japan w-100 py-3 rounded-pill fw-bold fs-5 shadow hover-scale mb-4">
                            Login to your account
                        </button>

                        <!-- Divider -->
                        <div class="position-relative mb-4">
                            <hr class="my-4">
                            <span
                                class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-secondary text-uppercase small fw-bold"
                                style="letter-spacing: 0.1em; font-size: 11px;">
                                Or continue with
                            </span>
                        </div>

                        <!-- Social Login Buttons -->
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

                        <!-- Sign Up Link -->
                        <p class="text-center text-secondary mb-0">
                            Don't have an account?
                            <a href="{{ url('/signup') }}" class="text-japan-red text-decoration-none fw-bold">Sign up
                                for free</a>
                        </p>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = 'visibility';
            }
        }
    </script>

</body>

</html>