<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<head>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        /* ── Rewards Page ── */
        .rewards-balance-card {
            background: #fff;
            border: 1px solid #f0eeee;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
            padding: 28px;
        }

        .points-display {
            font-size: 3rem;
            font-weight: 800;
            color: #BC002D;
            line-height: 1;
        }

        .points-unit {
            font-size: 1rem;
            font-weight: 500;
            color: #6b7280;
            margin-left: 6px;
        }

        .tier-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .tier-bronze {
            background: #fed7aa;
            color: #9a3412;
        }

        .tier-gold {
            background: #fef3c7;
            color: #b45309;
        }

        .tier-silver {
            background: #f3f4f6;
            color: #4b5563;
        }

        .tier-master {
            background: #fce7f3;
            color: #be185d;
        }

        /* Progress bar */
        .tier-progress-track {
            height: 10px;
            background: #f3f4f6;
            border-radius: 999px;
            overflow: hidden;
        }

        .tier-progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #BC002D, #ef4444);
            transition: width .6s ease;
        }

        /* Perks row */
        .perk-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .perk-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .perk-icon.red {
            background: rgba(188, 0, 45, .1);
            color: #BC002D;
        }

        .perk-icon.amber {
            background: #fef3c7;
            color: #d97706;
        }

        .perk-icon.violet {
            background: #ede9fe;
            color: #7c3aed;
        }

        /* Reward cards */
        .reward-card {
            background: #fff;
            border: 1px solid #f0eeee;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
            transition: transform .25s, box-shadow .25s;
        }

        .reward-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        .reward-card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .reward-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .reward-badge.limited {
            background: #111827;
            color: #fff;
        }

        .reward-badge.exclusive {
            background: #BC002D;
            color: #fff;
        }

        .reward-badge.new {
            background: #059669;
            color: #fff;
        }

        .reward-pts {
            font-weight: 700;
            color: #BC002D;
            font-size: 15px;
        }

        /* Activity table */
        .activity-card {
            background: #fff;
            border: 1px solid #f0eeee;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .activity-table thead th {
            background: #fafafa;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #9ca3af;
            font-weight: 600;
            border: none;
            padding: 12px 20px;
        }

        .activity-table tbody td {
            padding: 14px 20px;
            font-size: 14px;
            border-color: #f3f4f6;
            vertical-align: middle;
        }

        .pts-positive {
            color: #059669;
            font-weight: 600;
        }

        .pts-negative {
            color: #dc2626;
            font-weight: 600;
        }
    </style>
</head>

<body class="dashboard-page">

    @include('partials.header')
    @include('partials.sidebar')

    <main class="main-content p-4 p-lg-5" style="min-height: 100vh;">
        <script>
            function toggleSidebar() {
                document.getElementById('sidebar').classList.toggle('show');
            }
        </script>

        {{-- ─── Page Header ─── --}}
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <p class="text-uppercase fw-bold small mb-1" style="color: #BC002D; letter-spacing: 1px;">Loyalty
                    Program</p>
                <h1 class="fw-bold text-dark mb-1" style="font-size: 1.85rem;">Waku Rewards</h1>
                <p class="text-secondary mb-0" style="font-size: 14px;">Earn points, level up, and unlock exclusive
                    Japanese experiences.</p>
            </div>
        </div>

        {{-- ─── Current Balance Card ─── --}}
        <div class="rewards-balance-card mb-5">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div>
                    <p class="fw-bold text-dark mb-2">Current Balance</p>
                    <div class="d-flex align-items-baseline">
                        <span class="points-display">{{ number_format($totalPoints) }}</span>
                        <span class="points-unit">Waku Points</span>
                    </div>
                </div>
                <div class="text-md-end mt-3 mt-md-0">
                    <span class="tier-badge {{ $currentTier['class'] }} mb-2">
                        <span class="material-icons" style="font-size: 16px;">star</span>
                        {{ $currentTier['name'] }}
                    </span>
                    @if($currentTier['next'])
                        <p class="text-secondary mb-0 mt-2" style="font-size: 13px;">Next Tier: <strong
                                class="text-dark">{{ $currentTier['next'] }}</strong></p>
                    @else
                        <p class="text-secondary mb-0 mt-2" style="font-size: 13px;"><strong class="text-dark">Max Tier
                                Reached!</strong></p>
                    @endif
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="mb-2">
                @if($currentTier['nextMin'])
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-secondary fw-medium"
                            style="font-size: 11px;">{{ number_format($currentTier['min']) }} PTS</small>
                        <small class="text-secondary fw-medium" style="font-size: 11px;">{{ number_format($ptsToUpgrade) }}
                            PTS LEFT TO UPGRADE</small>
                        <small class="text-secondary fw-medium"
                            style="font-size: 11px;">{{ number_format($currentTier['nextMin']) }} PTS</small>
                    </div>
                @else
                    <div class="d-flex justify-content-center mb-1">
                        <small class="text-secondary fw-medium" style="font-size: 11px;">🎉 You've reached the highest
                            tier!</small>
                    </div>
                @endif
                <div class="tier-progress-track">
                    <div class="tier-progress-fill" style="width: {{ $progressPct }}%;"></div>
                </div>
            </div>

            {{-- Perks --}}
            {{-- <div class="row g-3 mt-3 pt-3 border-top">
                <div class="col-md-4">
                    <div class="perk-item">
                        <div class="perk-icon red">
                            <span class="material-icons" style="font-size: 22px;">local_fire_department</span>
                        </div>
                        <div>
                            <p class="fw-bold text-dark mb-0" style="font-size: 14px;">Earn 2× Points</p>
                            <small class="text-secondary" style="font-size: 12px;">On all Kyoto bookings</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="perk-item">
                        <div class="perk-icon amber">
                            <span class="material-icons" style="font-size: 22px;">luggage</span>
                        </div>
                        <div>
                            <p class="fw-bold text-dark mb-0" style="font-size: 14px;">Free Luggage</p>
                            <small class="text-secondary" style="font-size: 12px;">Delivery service included</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="perk-item">
                        <div class="perk-icon violet">
                            <span class="material-icons" style="font-size: 22px;">airline_seat_individual_suite</span>
                        </div>
                        <div>
                            <p class="fw-bold text-dark mb-0" style="font-size: 14px;">Lounge Access</p>
                            <small class="text-secondary" style="font-size: 12px;">At major JP airports</small>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

        {{-- ─── Available Rewards ─── --}}
        <h4 class="fw-bold text-dark mb-3">Available Rewards</h4>

        @if($rewardItems->count() > 0)
            <div class="row g-4 mb-5">
                @foreach($rewardItems as $reward)
                    <div class="col-md-4">
                        <div class="reward-card h-100 d-flex flex-column">
                            <div class="position-relative">
                                <img src="{{ $reward->image ?? app_setting('default_tour_image', 'https://images.unsplash.com/photo-1545569341-9eb8b30979d9?w=400&h=300&fit=crop') }}"
                                    alt="{{ $reward->title }}" class="reward-card-img">
                                @if($reward->badge)
                                    <span class="reward-badge {{ $reward->badge_class }}">{{ $reward->badge }}</span>
                                @endif
                            </div>
                            <div class="p-3 d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1">{{ $reward->title }}</h6>
                                <p class="text-secondary mb-3 flex-grow-1" style="font-size: 13px;">{{ $reward->description }}
                                </p>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="reward-pts">{{ number_format($reward->points_cost) }} pts</span>
                                    @if($totalPoints >= $reward->points_cost)
                                        <form action="{{ route('rewards.redeem') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                                            <button type="submit" class="btn btn-sm text-white rounded-3 px-3"
                                                style="background: #BC002D; font-size: 13px;">Redeem</button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary rounded-3 px-3"
                                            style="font-size: 13px;">Details</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 mb-5">
                <span class="material-icons text-secondary mb-2" style="font-size: 48px; opacity: 0.3;">redeem</span>
                <p class="text-secondary">No rewards available at the moment. Check back soon!</p>
            </div>
        @endif

        {{-- ─── Recent Points Activity ─── --}}
        <div class="activity-card mb-5">
            <div class="d-flex justify-content-between align-items-center p-4 pb-0">
                <h5 class="fw-bold text-dark mb-0">Recent Points Activity</h5>
            </div>
            @if($activities->count() > 0)
                <div class="table-responsive">
                    <table class="table activity-table mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Booking ID</th>
                                <th class="text-end">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td class="text-secondary">{{ $activity->created_at->format('M d, Y') }}</td>
                                    <td class="fw-medium text-dark">{{ $activity->description }}</td>
                                    <td class="text-secondary">
                                        {{ $activity->booking ? '#' . $activity->booking->booking_code : '-' }}
                                    </td>
                                    <td class="text-end {{ $activity->points > 0 ? 'pts-positive' : 'pts-negative' }}">
                                        {{ $activity->points > 0 ? '+' : '' }}{{ number_format($activity->points) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <span class="material-icons text-secondary mb-2"
                        style="font-size: 48px; opacity: 0.3;">receipt_long</span>
                    <p class="text-secondary mb-0">No point activity yet. Complete a trip to start earning!</p>
                </div>
            @endif
        </div>

    </main>

    @include('partials.script')

</body>

</html>