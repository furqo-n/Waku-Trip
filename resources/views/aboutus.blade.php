<!DOCTYPE html>
<html lang="en">

@section('page_title', 'About Us – Waku Trip | Our Story & Mission')
@section('meta_description', 'Learn about Waku Trip – born from a love of authentic Japan. Discover our mission, values, and the passionate local team that crafts unforgettable Japanese travel experiences.')
@include('partials.head')

<body class="bg-shape">

    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->
    <main>
        <section class="section-margin mt-5 px-3 px-lg-5">
            <div class="container-fluid">
                <div class="hero-card shadow-lg group">
                    <div class="hero-bg"></div>
                    <div class="hero-overlay"></div>

                    <div class="position-relative z-1 text-center text-white px-3" style="max-width: 800px;">
                        <span
                            class="badge bg-primary bg-opacity-75 rounded-pill px-3 py-2 text-uppercase tracking-wider mb-3 shadow border-0">Our
                            Story</span>
                        <h1 class="display-3 fw-black mb-4 lh-1 text-white">Born from a love of <br /> the unseen Japan.
                        </h1>
                        <p class="lead fw-medium text-light mx-auto" style="max-width: 600px;">
                            We started with a simple backpack and a rail pass. Today, we're sharing the hidden alleys,
                            mountain shrines, and local flavors that guidebooks miss.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-surface-light">
            <div class="container py-5 text-center" style="max-width: 900px;">
                <div class="d-flex align-items-center justify-content-center gap-2 text-primary opacity-50 mb-4">
                    <span class="material-symbols-outlined">water_drop</span>
                    <span class="d-inline-block bg-primary" style="height: 1px; width: 50px;"></span>
                    <span class="material-symbols-outlined">water_drop</span>
                </div>
                <h2 class="display-5 fw-bold mb-4 text-dark">Our Mission</h2>
                <p class="fs-3 fw-medium text-secondary lh-base">
                    "To connect the curious traveler with the <span class="text-primary text-decoration-underline"
                        style="text-decoration-style: wavy !important; text-underline-offset: 6px;">beating heart of
                        authentic Japan</span>, moving beyond the postcard to find the people and stories that make this
                    country extraordinary."
                </p>
            </div>
        </section>

        <section class="py-5 bg-white">
            <div class="container py-5">
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold mb-3">Why Waku Trip?</h2>
                    <p class="text-secondary">Experience Japan like a local, not a tourist.</p>
                </div>

                <div class="row g-5">
                    <div class="col-md-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-surface-light text-primary mb-4 shadow-sm hover-scale"
                            style="width: 80px; height: 80px;">
                            <span class="material-symbols-outlined fs-1">travel_explore</span>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Local Expertise</h3>
                        <p class="text-secondary">Our guides aren't just visitors; they are locals, historians, and
                            artisans who live and breathe the culture every day.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-surface-light text-primary mb-4 shadow-sm hover-scale"
                            style="width: 80px; height: 80px;">
                            <span class="material-symbols-outlined fs-1">fingerprint</span>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Personalized Service</h3>
                        <p class="text-secondary">No two trips are alike. We craft bespoke itineraries that match your
                            pace, interests, and curiosity level perfectly.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-surface-light text-primary mb-4 shadow-sm hover-scale"
                            style="width: 80px; height: 80px;">
                            <span class="material-symbols-outlined fs-1">eco</span>
                        </div>
                        <h3 class="h4 fw-bold mb-3">Sustainable Travel</h3>
                        <p class="text-secondary">We prioritize eco-friendly transport and support local economies,
                            ensuring our footprint is as light as a falling cherry blossom.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-surface-light">
            <div class="container py-lg-5">
                <h2 class="text-center display-6 fw-bold mb-5">Our Values</h2>

                <div class="values-grid">
                    <div class="value-card grid-span-2-row-2 shadow">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7jI24gRh6mc81i3aNAj-mlaYu1NWitMdn7aQMSS6RSY_SWGaI_Fd9e1PZ5jiooVP46uWydtiwMrlmGmYrGFnOndxwTFCDzDXTR7tPfnnsSD87omfwecP8-MpfTuPaqG8ye7AKRODJiMvWnF3SfG2JMMYxZPH6KsEFF8yMeVgTAhLgqH8T0MXs1NSVV9_TkTyq7qz4dJ1L5PE7olNay9y-mIkouGWXhvYDOw0P03iM_wxNELCG-29IJcDDR4SFdzBEhW5-LOu6lKU"
                            class="value-img" alt="Authenticity">
                        <div class="value-overlay">
                            <h3 class="h2 fw-bold text-white mb-2">Authenticity</h3>
                            <p class="text-white-50 mb-0 reveal-text">We honor the true traditions, not the tourist
                                version.</p>
                        </div>
                    </div>

                    <div class="value-card grid-span-1 shadow">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDD06FbFuoegLY5sP900NW3pZinPsLn3B43HbdEXVfahBaUIkjWH8Nd42p45TX7M2YtQw4htL4ovbrBlHlJbNQ0M9HicfIVsDXnPrEgNVZQXIiQEhCyV7MagLdklw-7yI4wWniRN-qn9sn5LU-35AqFfT__XCMiAjBAzHenkP2dhsix8rGwyacv2myI8Ypsvp9SECjRSxdNKwg-MJvEKBLJOrC2vCUc2iFngZk60p2LCPnL0dytgLWCmHVwj0y5-qX-l0wYndN3-2w"
                            class="value-img" alt="Craftsmanship">
                        <div class="value-overlay">
                            <h3 class="h4 fw-bold text-white mb-1">Craftsmanship</h3>
                            <p class="small text-white-50 mb-0 reveal-text">Dedication to detail.</p>
                        </div>
                    </div>

                    <div class="value-card grid-span-1 shadow">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDv2ueBQ4a7SuKL60seT81Y7lKieURTVfRcwMGQx-bCH0n-X228uwyeWiKTobgRrOqRxrUDgDiQxBYWaBRz5rkOZlTdG6oVlStQjlN6n0xHcw7c2O1yVBlkowsOt15Y7f2ouORhhAY_89x-2y_B-LPAbTBYAuSSFnDK-IlQOHoxM9ncLDHFfWIHgQb5tjKAQDvyCCYvdR6VMNUPwgCdBG4cjTXlT7l2qCfa-GVt0K6smXmgL-zH6DjYN0TkyNFTw9SNh2oe_-J9Ls8"
                            class="value-img" alt="Harmony">
                        <div class="value-overlay">
                            <h3 class="h4 fw-bold text-white mb-1">Harmony</h3>
                            <p class="small text-white-50 mb-0 reveal-text">Respecting nature and culture.</p>
                        </div>
                    </div>

                    <div class="value-card grid-span-2 shadow">
                        <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGaCqTK5vTYobuTtyQi9tKP-AbZkgH0khoFnD2-ENNOdYwXwCFOYceM1I-sehZat7pHc01mWw0KNaPvDJrgA_CQByxOoatZekKtTHRQAGvCHpfFoWkcLfEZmOsMqsU7jOxo9zKyb8dvJzYW_b4FVP225lmo1SsUjc_XBHFsby7znGdb5lege-K1SWbSPZeRBGCCwzfWTp_CTZzIEwPqtb6z99dZRq_CKEwJcBbUJjov9NQXTZrzVBSd4-l85fWOHUs8NuYhBgoG8A"
                            class="value-img" alt="Connection">
                        <div class="value-overlay">
                            <h3 class="h3 fw-bold text-white mb-1">Connection</h3>
                            <p class="text-white-50 mb-0 reveal-text">Building bridges between people.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-white">
            <div class="container py-5">
                <div class="text-center mb-5">
                    <span class="text-primary fw-bold text-uppercase small ls-1">The People</span>
                    <h2 class="display-5 fw-bold mt-2">Meet the Team</h2>
                    <p class="text-secondary mt-3">Passionate travelers, local experts, and storytellers dedicated to
                        your journey.</p>
                </div>

                <div class="row g-5 justify-content-center">
                    <div class="col-sm-6 col-lg-3 text-center">
                        <div class="mx-auto team-img-wrapper">
                            <img src="{{ 'img/user_profile.png' }}" class="w-100 h-100 object-fit-cover"
                                alt="Hiroshi Sato">
                        </div>
                        <h4 class="fw-bold mb-1">Furqon Kun</h4>
                        <p class="text-primary fw-bold small mb-2">Local Guide Lead</p>
                        <p class="small text-secondary">Master of vidio gaming and sleeping. Believes the journey
                            is a waste.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-surface-light position-relative overflow-hidden">
            <div class="position-absolute top-0 end-0 opacity-25" style="width: 400px; z-index: 0;">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#BC002D"
                        d="M42.7,-62.9C50.9,-52.8,49.8,-34.4,52.3,-18.6C54.8,-2.8,60.9,10.5,58.3,22.2C55.7,33.9,44.4,44.1,31.9,50.6C19.4,57.1,5.7,59.9,-6.2,57.5C-18.1,55.1,-28.1,47.5,-39.8,38.6C-51.5,29.7,-64.8,19.5,-69.1,6.5C-73.4,-6.5,-68.6,-22.3,-58.5,-33.4C-48.4,-44.5,-32.9,-50.9,-19.4,-57.4C-5.9,-63.9,5.6,-70.5,18.8,-70.2C32,-69.9,46.9,-62.7,42.7,-62.9Z"
                        transform="translate(100 100)" opacity="0.1" />
                </svg>
            </div>

            <div class="container position-relative z-1 py-5">
                <div class="bg-white rounded-5 p-5 p-lg-5 text-center shadow-lg border border-light mx-auto"
                    style="max-width: 900px;">
                    <h2 class="display-5 fw-bold mb-4 text-dark">Ready to discover the real Japan?</h2>
                    <p class="fs-5 text-secondary mb-5">Join us on a journey that goes beyond the ordinary. Let's create
                        memories that last a lifetime.</p>

                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3">
                        <button class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm hover-scale">Start
                            Your Journey</button>
                        <button class="btn btn-outline-dark btn-lg rounded-pill px-5 fw-bold hover-scale">View
                            Itineraries</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!--================ Footer Area start =================-->
    @include('partials.footer')
    <!--=============== Footer Area end =================-->

</body>

</html>