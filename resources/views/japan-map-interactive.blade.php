<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Japan Map</title>
    <!-- Use Google Fonts for a premium look -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .map-container {
            display: flex;
            gap: 40px;
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            /* Softer shadow */
            position: relative;
            overflow: hidden;
            /* For cleaner corners */
        }

        /* Navigation Arrows */
        .info-nav {
            position: absolute;
            top: 30px;
            right: 30px;
            display: flex;
            gap: 12px;
            z-index: 10;
        }

        .nav-button {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: white;
            /* White button */
            border: 1px solid #e2e8f0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .nav-button:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e0;
        }

        .nav-button svg path {
            fill: #4a5568;
            /* Dark gray arrow */
            transition: fill 0.3s;
        }

        .nav-button:hover svg path {
            fill: #2d3748;
        }

        /* Info Box (Left Side) */
        .info-box {
            flex: 1;
            max-width: 480px;
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity 0.4s ease, transform 0.4s ease;
            /* Hidden by default until JS runs */
            display: none;
        }

        .info-box.visible {
            opacity: 1;
            transform: translateX(0);
            display: block;
        }

        .info-images {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 24px;
        }

        .info-image {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 16px;
            transition: transform 0.3s ease;
            background-color: #edf2f7;
            /* Placeholder color */
        }

        .info-image:hover {
            transform: scale(1.02);
        }

        .heading-text {
            font-size: 36px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .cities {
            font-size: 14px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }

        .description {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 32px;
        }

        .activities-title {
            display: block;
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 16px;
        }

        .product-assign {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 24px;
        }

        .product-link {
            text-decoration: none;
            display: block;
        }

        .product-item {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.2s ease;
        }

        .product-item:hover {
            border-color: #cbd5e0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transform: translateX(4px);
        }

        .product-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            background-color: #edf2f7;
        }

        .product-item span {
            font-size: 15px;
            color: #2d3748;
            font-weight: 500;
        }

        .more-link {
            display: inline-flex;
            align-items: center;
            color: #3182ce;
            /* Blue link */
            font-weight: 600;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.2s;
        }

        .more-link:hover {
            color: #2b6cb0;
            text-decoration: underline;
        }

        /* Map Section (Right Side) */
        .map-column {
            flex: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .svg-content {
            width: 100%;
            height: auto;
            /* Ensure aspect ratio is maintained */
            aspect-ratio: 624/588;
        }

        /* SVG Styles */
        .map-region {
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .map-path {
            fill: #e2e8f0;
            /* Default gray */
            transition: fill 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            stroke: #fff;
            stroke-width: 1.5;
        }

        .map-path-text {
            fill: #718096;
            /* Text color inside map */
            pointer-events: none;
        }

        /* Hover & Active States */
        .map-region:hover .map-path {
            fill: #90cdf4;
            /* Light blue on hover */
        }

        .map-region.active-region .map-path {
            fill: #3182ce;
            /* Solid blue for active */
        }

        .map-region.active-region .map-path-text {
            fill: white;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .map-container {
                flex-direction: column-reverse;
                padding: 30px;
            }

            .info-nav {
                top: 20px;
                right: 20px;
            }

            .info-box {
                max-width: 100%;
            }

            .product-assign {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {
            .product-assign {
                grid-template-columns: 1fr;
            }

            .info-images {
                grid-template-columns: repeat(4, 1fr);
                /* scrollable maybe? kept grid for now */
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <div class="map-container">
        <!-- Navigation Buttons -->
        <div class="info-nav">
            <button id="prev-region" class="nav-button" aria-label="Previous Region">
                <svg width="10" height="16" viewBox="0 0 8 13" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M0.292282 5.65703L5.94928 2.67029e-05L7.36328 1.41403L2.41328 6.36403L7.36328 11.314L5.94928 12.728L0.292282 7.07103C0.104811 6.8835 -0.000504971 6.62919 -0.000504971 6.36403C-0.000504971 6.09886 0.104811 5.84455 0.292282 5.65703Z" />
                </svg>
            </button>
            <button id="next-region" class="nav-button" aria-label="Next Region">
                <svg width="10" height="16" viewBox="0 0 8 13" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M7.071 7.071L1.414 12.728L0 11.314L4.95 6.364L0 1.414L1.414 0L7.071 5.657C7.25847 5.84453 7.36379 6.09884 7.36379 6.364C7.36379 6.62916 7.25847 6.88347 7.071 7.071Z" />
                </svg>
            </button>
        </div>

        <!-- Info Box (Content changes dynamically) -->
        <div id="info-box" class="info-box visible">
            <div class="info-images">
                <!-- Images injected by JS -->
                <img src="" class="info-image" alt="Region Img 1">
                <img src="" class="info-image" alt="Region Img 2">
                <img src="" class="info-image" alt="Region Img 3">
                <img src="" class="info-image" alt="Region Img 4">
            </div>

            <div class="text-info">
                <h3 class="heading-text">Region Name</h3>
                <p class="cities">City | City | City</p>
                <p class="description">Region description goes here.</p>

                <span class="activities-title">Featured Activities</span>
                <div class="product-assign">
                    <!-- Products injected by JS -->
                </div>

                <div style="margin-top: 20px;">
                    <a href="#" class="more-link">Explore More →</a>
                </div>
            </div>
        </div>

        <!-- Map Column -->
        <div class="map-column">
            <div class="svg-content">
                <svg width="100%" height="100%" viewBox="0 0 624 588" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="svg-map">
                    <!-- SVG PATHS WILL GO HERE IN STEP 2 -->
                </svg>
            </div>
        </div>
    </div>

    <!-- SCRIPTS WILL GO HERE IN STEP 3 -->

</body>

</html>