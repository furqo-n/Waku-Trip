<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $booking->booking_code }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root {
            --primary: #BC002D;
            --primary-light: #fff0f3;
            --dark: #1a1a2e;
            --text: #333;
            --muted: #6c757d;
            --border: #e2e8f0;
            --bg: #f8f9fa;
            --success: #10b981;
            --warning: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            font-size: 12px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .no-print {
            text-align: center;
            padding: 14px;
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 24px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(188,0,45,0.3);
        }

        .btn-print:hover { background: #a00026; }
        .btn-print .material-icons { font-size: 16px; }

        /* ===== RECEIPT WRAPPER ===== */
        .receipt {
            width: 210mm;
            min-height: 270mm;
            max-height: 297mm; /* A4 */
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        .r-header {
            background: linear-gradient(135deg, var(--dark) 0%, #16213e 100%);
            padding: 18px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .r-header::after {
            content: '';
            position: absolute;
            right: -40px;
            top: -40px;
            width: 140px;
            height: 140px;
            background: radial-gradient(circle, rgba(188,0,45,0.35), transparent 70%);
            border-radius: 50%;
        }

        .brand { display: flex; align-items: center; gap: 10px; z-index: 1; }

        .brand-icon {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }

        .brand-icon .material-icons { font-size: 22px; color: #fff; }

        .brand h1 { font-size: 20px; font-weight: 800; }
        .brand small { display: block; font-size: 10px; color: rgba(255,255,255,0.55); letter-spacing: 1.5px; text-transform: uppercase; }

        .r-meta { text-align: right; z-index: 1; }

        .doc-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 4px;
        }

        .booking-code {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1px;
            background: rgba(255,255,255,0.1);
            padding: 4px 12px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 4px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .status-pill.paid, .status-pill.confirmed { background: rgba(16,185,129,0.2); color: #6ee7b7; }
        .status-pill.pending { background: rgba(245,158,11,0.2); color: #fcd34d; }
        .status-pill.cancelled { background: rgba(239,68,68,0.2); color: #fca5a5; }
        .status-pill .material-icons { font-size: 11px; }

        /* ===== HERO STRIP (image + package name) ===== */
        .r-hero {
            position: relative;
            height: 90px;
            overflow: hidden;
        }

        .r-hero img {
            width: 100%; height: 100%; object-fit: cover;
        }

        .r-hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to right, rgba(0,0,0,0.65) 40%, transparent 100%);
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .r-hero-overlay h2 {
            color: #fff;
            font-size: 16px;
            font-weight: 800;
        }

        .r-hero-overlay p {
            color: rgba(255,255,255,0.75);
            font-size: 11px;
            margin-top: 2px;
        }

        /* ===== BODY ===== */
        .r-body {
            padding: 16px 24px;
            flex: 1;
        }

        /* two-column top row */
        .r-top {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 12px;
        }

        .info-box {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
        }

        .info-box .box-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            margin-bottom: 5px;
        }

        .info-box .row { display: flex; justify-content: space-between; margin-bottom: 1px; }
        .info-box .row span:first-child { color: var(--muted); }
        .info-box .row span:last-child { font-weight: 600; color: var(--dark); }

        /* detail chips row */
        .r-chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: var(--bg);
            font-size: 11px;
        }

        .chip .material-icons { font-size: 14px; color: var(--primary); }
        .chip strong { color: var(--dark); }

        /* ===== PRICE TABLE ===== */
        .section-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid var(--border);
        }

        .r-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 10px;
        }

        .price-rows { }

        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 4px 0;
            border-bottom: 1px dashed var(--border);
            font-size: 11px;
        }

        .price-row:last-child { border-bottom: none; }
        .price-row .pr-label { color: var(--muted); }
        .price-row .pr-value { font-weight: 600; color: var(--dark); }
        .price-row.discount .pr-label { color: var(--success); }
        .price-row.discount .pr-value { color: var(--success); }

        .total-bar {
            background: linear-gradient(135deg, var(--dark), #16213e);
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 16px;
            margin-bottom: 12px;
            color: #fff;
        }

        .total-bar .tl { font-size: 11px; opacity: 0.7; }
        .total-bar .tv { font-size: 18px; font-weight: 800; letter-spacing: -0.5px; }

        /* ===== PASSENGER TABLE ===== */
        .r-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .r-table th {
            background: var(--bg);
            padding: 6px 10px;
            text-align: left;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            border-bottom: 2px solid var(--border);
            font-weight: 700;
        }

        .r-table td {
            padding: 6px 10px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        .p-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 22px; height: 22px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 10px;
            font-weight: 700;
            margin-right: 6px;
        }

        /* inclusions */
        .inc-tags { display: flex; flex-wrap: wrap; gap: 4px; }
        .inc-tag {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 8px;
            background: #ecfdf5;
            color: var(--success);
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
        }

        .inc-tag .material-icons { font-size: 11px; }

        /* ===== FOOTER ===== */
        .r-footer {
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 24px;
            background: var(--bg);
        }

        .r-footer .thank { font-size: 11px; font-weight: 700; color: var(--primary); }
        .r-footer .support { font-size: 10px; color: var(--muted); }

        .watermark {
            background: rgba(188,0,45,0.06);
            border: 1px dashed rgba(188,0,45,0.2);
            border-radius: 4px;
            padding: 3px 10px;
            font-size: 10px;
            font-weight: 700;
            color: var(--primary);
            letter-spacing: 1px;
        }

        /* ===== PRINT ===== */
        @media print {
            @page { size: A4; margin: 0; }
            body { background: #fff; }
            .no-print { display: none !important; }
            .receipt {
                width: 100%;
                max-height: none;
                border-radius: 0;
                box-shadow: none;
                margin: 0;
            }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="btn-print" onclick="window.print()">
        <span class="material-icons">print</span>
        Print / Save as PDF
    </button>
</div>

<div class="receipt">

    {{-- HEADER --}}
    <div class="r-header">
        <div class="brand">
            <div class="brand-icon"><span class="material-icons">flight_takeoff</span></div>
            <div>
                <h1>Waku Trip</h1>
                <small>Your Japan Travel Partner</small>
            </div>
        </div>
        <div class="r-meta">
            <div class="doc-title">Official Receipt</div>
            <div class="booking-code">{{ $booking->booking_code }}</div>
            <div>
                <span class="status-pill {{ $booking->status }}">
                    <span class="material-icons">
                        @if(in_array($booking->status, ['paid','confirmed'])) check_circle
                        @elseif($booking->status === 'cancelled') cancel
                        @else schedule @endif
                    </span>
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- HERO --}}
    @if($imageUrl)
    <div class="r-hero">
        <img src="{{ $imageUrl }}" alt="{{ $schedule->package->title }}">
        <div class="r-hero-overlay">
            <div>
                <h2>{{ $schedule->package->title }}</h2>
                <p>{{ $schedule->start_date->format('M d') }} – {{ $schedule->end_date->format('M d, Y') }} &nbsp;·&nbsp; {{ $durationDays }} Days {{ $durationDays - 1 }} Nights</p>
            </div>
        </div>
    </div>
    @endif

    {{-- BODY --}}
    <div class="r-body">

        {{-- Top row: Customer / Payment --}}
        <div class="r-top">
            <div class="info-box">
                <div class="box-label">Customer</div>
                <div class="row"><span>Name</span><span>{{ optional($booking->user)->name ?? 'Guest' }}</span></div>
                <div class="row"><span>Email</span><span>{{ optional($booking->user)->email ?? '-' }}</span></div>
                <div class="row"><span>Issued</span><span>{{ $booking->created_at->format('M d, Y') }}</span></div>
            </div>
            <div class="info-box">
                <div class="box-label">Payment</div>
                @if($booking->payment)
                    <div class="row"><span>Method</span><span>{{ ucfirst($booking->payment->payment_method ?? 'Card') }}</span></div>
                    <div class="row"><span>Transaction</span><span>{{ $booking->payment->transaction_id ?? '-' }}</span></div>
                    <div class="row"><span>Paid At</span><span>{{ $booking->payment->paid_at ? $booking->payment->paid_at->format('M d, Y') : '-' }}</span></div>
                @else
                    <div class="row"><span>Status</span><span>{{ ucfirst($booking->status) }}</span></div>
                    <div class="row"><span>Travelers</span><span>{{ $booking->pax_count }} Pax</span></div>
                    <div class="row"><span>Date</span><span>{{ $booking->created_at->format('M d, Y') }}</span></div>
                @endif
            </div>
        </div>

        {{-- Trip chips --}}
        <div class="r-chips">
            <div class="chip"><span class="material-icons">people</span><strong>{{ $booking->pax_count }} Pax</strong></div>
            <div class="chip"><span class="material-icons">calendar_today</span><strong>{{ $schedule->start_date->format('M d') }} – {{ $schedule->end_date->format('M d, Y') }}</strong></div>
            <div class="chip"><span class="material-icons">schedule</span><strong>{{ $durationDays }} Days / {{ $durationDays - 1 }} Nights</strong></div>
            <div class="chip"><span class="material-icons">confirmation_number</span><strong>{{ $booking->booking_code }}</strong></div>
        </div>

        {{-- Price + Inclusions --}}
        <div class="r-columns">
            <div>
                <div class="section-label">Price Breakdown</div>
                <div class="price-rows">
                    <div class="price-row">
                        <span class="pr-label">Base ({{ convert_currency($pricePerPerson) }} × {{ $booking->pax_count }})</span>
                        <span class="pr-value">{{ convert_currency($basePrice) }}</span>
                    </div>
                    <div class="price-row">
                        <span class="pr-label">PPN (12%)</span>
                        <span class="pr-value">{{ convert_currency($ppn) }}</span>
                    </div>
                    <div class="price-row">
                        <span class="pr-label">Service Fee (10%)</span>
                        <span class="pr-value">{{ convert_currency($fee) }}</span>
                    </div>
                    <div class="price-row discount">
                        <span class="pr-label">Early Bird Discount (7%)</span>
                        <span class="pr-value">-{{ convert_currency($discount) }}</span>
                    </div>
                </div>
            </div>

            @if($schedule->package->inclusions && $schedule->package->inclusions->where('is_included', true)->count() > 0)
            <div>
                <div class="section-label">What's Included</div>
                <div class="inc-tags">
                    @foreach($schedule->package->inclusions->where('is_included', true) as $inc)
                        <span class="inc-tag"><span class="material-icons">check</span>{{ $inc->item }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Total --}}
        <div class="total-bar">
            <span class="tl">Total Amount</span>
            <span class="tv">{{ convert_currency($booking->total_price) }}</span>
        </div>

        {{-- Passengers --}}
        @if($booking->passengers->count() > 0)
        <div class="section-label">Passenger Details</div>
        <table class="r-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Passport</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <tbody>
                @foreach($booking->passengers as $i => $p)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><span class="p-avatar">{{ strtoupper(substr($p->name, 0, 1)) }}</span>{{ $p->name }}</td>
                    <td>{{ $p->passport_number ?? '-' }}</td>
                    <td>{{ $p->date_of_birth ? $p->date_of_birth->format('M d, Y') : '-' }}</td>
                    <td>{{ $p->gender ? ucfirst($p->gender) : '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($booking->special_requests)
        <div class="info-box" style="margin-top:8px;">
            <div class="box-label">Special Requests</div>
            <div style="font-size:11px; color:var(--text);">{{ $booking->special_requests }}</div>
        </div>
        @endif

    </div>

    {{-- FOOTER --}}
    <div class="r-footer">
        <div>
            <div class="thank">🎌 Thank you for choosing Waku Trip!</div>
            <div class="support">admin@wakutrip.com &nbsp;·&nbsp; www.wakutrip.com</div>
        </div>
        <div class="watermark">OFFICIAL RECEIPT</div>
    </div>

</div>

</body>
</html>
