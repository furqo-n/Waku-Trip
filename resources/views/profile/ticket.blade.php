@php
    $passengers = isset($passengers) ? $passengers : [$passenger];
    $schedule   = $booking->tripSchedule;
    $package    = $schedule->package;
    $nights     = $schedule->start_date->diffInDays($schedule->end_date);
    $duration   = $nights . 'N/' . ($nights + 1) . 'D';

    $locationParts = explode(',', $package->location_text ?? 'Japan');
    $country       = trim($locationParts[0] ?? 'Japan');
    $city          = trim($locationParts[1] ?? $country);

    $destCode   = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $city), 0, 3));
    $originCode = 'CGK';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tickets – {{ $booking->booking_code }} | Waku Trip</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            padding: 32px 16px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* ── Toolbar ── */
        .toolbar {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
            width: 100%;
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-toolbar {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-back { background: #fff; color: #374151; border: 1px solid #e5e7eb; }
        .btn-back:hover { background: #f9fafb; }
        .btn-print { background: #BC002D; color: #fff; }
        .btn-print:hover { background: #a30026; }

        /* ── Boarding Pass Card ── */
        .pass-wrapper {
            width: 100%;
            max-width: 720px;
            margin-bottom: 40px;
            page-break-after: always;
        }

        .pass-wrapper:last-child { margin-bottom: 0; page-break-after: auto; }

        .boarding-pass {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            overflow: hidden;
            position: relative;
        }

        .pass-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 60%, #BC002D 100%);
            padding: 28px 36px 80px;
            position: relative;
            overflow: hidden;
        }

        .pass-header::before {
            content: '';
            position: absolute;
            right: -60px; top: -60px;
            width: 260px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }

        .pass-airline { display: flex; align-items: center; justify-content: space-between; }
        .pass-airline-logo { font-size: 28px; font-weight: 800; color: #fff; letter-spacing: -1px; }
        .pass-airline-logo span { color: #BC002D; }
        .pass-type { background: rgba(255,255,255,0.15); color: #fff; font-size: 11px; font-weight: 600; letter-spacing: 2px; padding: 5px 14px; border-radius: 20px; text-transform: uppercase; backdrop-filter: blur(4px); }

        .pass-route { display: flex; align-items: center; justify-content: space-between; margin-top: 28px; position: relative; z-index: 1; }
        .route-city { text-align: center; }
        .route-code { font-size: 48px; font-weight: 800; color: #fff; letter-spacing: -2px; line-height: 1; font-family: 'JetBrains Mono', monospace; }
        .route-name { font-size: 12px; color: rgba(255,255,255,0.65); font-weight: 500; margin-top: 4px; }
        .route-line { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 0 24px; }
        .route-line-bar { width: 100%; height: 1px; background: rgba(255,255,255,0.3); position: relative; }
        .route-plane-icon { font-size: 26px; color: rgba(255,255,255,0.8); }
        .route-duration { font-size: 11px; color: rgba(255,255,255,0.55); font-weight: 500; }

        .pass-divider { display: flex; align-items: center; margin: 0 -1px; position: relative; }
        .pass-divider-line { flex: 1; border-top: 2px dashed #e5e7eb; margin: 0 20px; }
        .pass-divider-circle { width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; flex-shrink: 0; margin-top: -18px; }
        .pass-divider-circle.left { margin-left: -18px; }
        .pass-divider-circle.right { margin-right: -18px; }

        .pass-body { padding: 28px 36px; }
        .pass-passenger { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 28px; padding-bottom: 28px; border-bottom: 1px solid #f0f0f0; }
        .pass-label { font-size: 10px; font-weight: 700; letter-spacing: 1.5px; color: #9ca3af; text-transform: uppercase; margin-bottom: 4px; }
        .pass-value { font-size: 15px; font-weight: 700; color: #111827; }
        .pass-value.large { font-size: 22px; }
        .pass-value.mono { font-family: 'JetBrains Mono', monospace; font-size: 14px; color: #BC002D; }

        .pass-info-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
        .pass-barcode-section { background: #f8fafc; border-radius: 16px; padding: 20px; display: flex; align-items: center; gap: 24px; }
        .barcode-visual { display: flex; gap: 3px; align-items: flex-end; height: 64px; flex-shrink: 0; }
        .barcode-bar { background: #111827; border-radius: 2px; }
        .barcode-text { font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: #374151; letter-spacing: 3px; margin-top: 8px; }
        .barcode-info { flex: 1; }
        .pass-status-badge { display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 700; margin-bottom: 8px; }
        .pass-status-badge::before { content: '●'; font-size: 8px; }

        .pass-footer { padding: 16px 36px; border-top: 1px solid #f0f0f0; display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #9ca3af; }

        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .boarding-pass { box-shadow: none; border-radius: 0; }
            .pass-divider-circle { display: none; }
            .pass-wrapper { margin-top: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="toolbar">
            <a href="{{ route('booking.manage', $booking->id) }}" class="btn-toolbar btn-back">← Back</a>
            <button class="btn-toolbar btn-print" onclick="window.print()">🖨 Print All Tickets</button>
        </div>

        @foreach($passengers as $index => $passenger)
            @php
                $ticketNumber = strtoupper($booking->booking_code) . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            @endphp
            <div class="pass-wrapper">
                <div class="boarding-pass">
                    <div class="pass-header">
                        <div class="pass-airline">
                            <div class="pass-airline-logo">Waku<span>Trip</span></div>
                            <div class="pass-type">E-Ticket / Boarding Pass</div>
                        </div>
                        <div class="pass-route">
                            <div class="route-city">
                                <div class="route-code">{{ $originCode }}</div>
                                <div class="route-name">Jakarta, Indonesia</div>
                            </div>
                            <div class="route-line">
                                <div class="route-plane-icon">✈</div>
                                <div class="route-line-bar"></div>
                                <div class="route-duration">{{ $duration }}</div>
                            </div>
                            <div class="route-city">
                                <div class="route-code">{{ $destCode }}</div>
                                <div class="route-name">{{ $city }}, {{ $country }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="pass-divider">
                        <div class="pass-divider-circle left"></div>
                        <div class="pass-divider-line"></div>
                        <div class="pass-divider-circle right"></div>
                    </div>

                    <div class="pass-body">
                        <div class="pass-passenger">
                            <div class="pass-passenger-info">
                                <div class="pass-label">Passenger Name</div>
                                <div class="pass-value large">{{ strtoupper($passenger->name) }}</div>
                                @if($passenger->passport_number)
                                    <div class="pass-value mono" style="margin-top: 4px;">PASSPORT: {{ strtoupper($passenger->passport_number) }}</div>
                                @endif
                            </div>
                            <div>
                                <div class="pass-label">Seat / Class</div>
                                <div class="pass-value">Economy</div>
                                <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">Waku Standard</div>
                            </div>
                        </div>

                        <div class="pass-info-grid">
                            <div><div class="pass-label">Departure Date</div><div class="pass-value">{{ $schedule->start_date->format('D, M d') }}</div><div style="font-size: 12px; color: #6b7280; margin-top: 2px;">{{ $schedule->start_date->format('Y') }}</div></div>
                            <div><div class="pass-label">Return Date</div><div class="pass-value">{{ $schedule->end_date->format('D, M d') }}</div><div style="font-size: 12px; color: #6b7280; margin-top: 2px;">{{ $schedule->end_date->format('Y') }}</div></div>
                            <div><div class="pass-label">Booking Code</div><div class="pass-value mono">{{ $booking->booking_code }}</div></div>
                            <div><div class="pass-label">Ticket No.</div><div class="pass-value mono">{{ $ticketNumber }}</div></div>
                            @if($passenger->date_of_birth)<div><div class="pass-label">Date of Birth</div><div class="pass-value">{{ $passenger->date_of_birth->format('d M Y') }}</div></div>@endif
                            @if($passenger->gender)<div><div class="pass-label">Gender</div><div class="pass-value">{{ ucfirst($passenger->gender) }}</div></div>@endif
                            <div><div class="pass-label">Package</div><div class="pass-value" style="font-size: 13px;">{{ Str::limit($package->title, 24) }}</div></div>
                            <div><div class="pass-label">Duration</div><div class="pass-value">{{ $nights + 1 }} Days / {{ $nights }} Nights</div></div>
                        </div>

                        <div class="pass-barcode-section">
                            <div class="barcode-container">
                                <div class="barcode-visual" id="barcode-{{ $index }}"></div>
                                <div class="barcode-text">{{ $ticketNumber }}</div>
                            </div>
                            <div class="barcode-info">
                                <div class="pass-status-badge">Confirmed</div>
                                <div class="pass-label" style="margin-bottom: 4px;">Important Notice</div>
                                <p style="font-size: 12px; color: #6b7280; line-height: 1.6;">Please present this e-ticket at the departure point. Contact Waku Trip support for any changes.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pass-footer">
                        <span>Waku Trip © {{ date('Y') }} — All rights reserved</span>
                        <span>Printed on {{ now()->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>
            </div>

            <script>
                (function() {
                    const barContainer = document.getElementById('barcode-{{ $index }}');
                    const widths = [1, 2, 1, 3, 1, 2, 2, 1, 3, 1, 2, 1, 3, 2, 1, 2, 1, 3, 2, 1, 2, 3, 1, 2, 1, 2, 3, 1, 2, 1];
                    widths.forEach(w => {
                        const bar = document.createElement('div');
                        bar.className = 'barcode-bar';
                        bar.style.width = w * 3 + 'px';
                        bar.style.height = (Math.random() * 24 + 40) + 'px';
                        barContainer.appendChild(bar);
                    });
                })();
            </script>
        @endforeach
    </div>
</body>
</html>
