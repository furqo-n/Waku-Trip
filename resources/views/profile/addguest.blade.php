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
    <main class="main-content p-4 p-lg-5" style="min-height: 100vh;">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">Manage Guests</h2>
                    <p class="text-secondary">Add details for your travel companions.</p>
                </div>
                <a href="{{ route('mybooking') }}" class="btn btn-outline-secondary rounded-3">
                    <i class="material-icons align-middle me-1">arrow_back</i> Back to Bookings
                </a>
            </div>

            <!-- Booking Summary -->
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap gap-4 align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px;">
                                <i class="material-icons text-white">tour</i>
                            </div>
                            <div>
                                <small class="text-secondary d-block">Package</small>
                                <strong class="text-dark">{{ $booking->tripSchedule->package->title }}</strong>
                            </div>
                        </div>
                        <div class="vr d-none d-md-block" style="height: 40px;"></div>
                        <div>
                            <small class="text-secondary d-block">Booking Code</small>
                            <strong class="text-dark">{{ $booking->booking_code }}</strong>
                        </div>
                        <div class="vr d-none d-md-block" style="height: 40px;"></div>
                        <div>
                            <small class="text-secondary d-block">Schedule</small>
                            <strong class="text-dark">
                                {{ $booking->tripSchedule->start_date->format('M d, Y') }} -
                                {{ $booking->tripSchedule->end_date->format('M d, Y') }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Guest List -->
                <div class="col-lg-7 mb-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="fw-bold m-0 d-flex align-items-center justify-content-between">
                                Passenger List
                                <span class="badge bg-primary rounded-pill">
                                    {{ $booking->passengers->count() }} / {{ $booking->pax_count }} Guests
                                </span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if($booking->passengers->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($booking->passengers as $index => $passenger)
                                        <div class="list-group-item p-4 d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle bg-light d-flex justify-content-center align-items-center text-secondary fw-bold"
                                                    style="width: 40px; height: 40px;">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1">{{ $passenger->name }}</h6>
                                                    <small class="text-secondary">
                                                        {{ $passenger->gender ? ucfirst($passenger->gender) : 'Gender not specified' }}
                                                        •
                                                        {{ $passenger->date_of_birth ? $passenger->date_of_birth->format('M d, Y') : 'DOB not specified' }}
                                                    </small>
                                                    @if($passenger->passport_number)
                                                        <div class="small text-secondary mt-1">
                                                            <i class="material-icons align-middle me-1"
                                                                style="font-size: 14px;">badge</i>
                                                            Passport: {{ $passenger->passport_number }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Delete Button -->
                                            @if($booking->status == 'pending' || $booking->status == 'confirmed')
                                                <form action="{{ route('booking.guests.destroy', $passenger->id) }}" method="POST"
                                                    onsubmit="return confirm('Remove this passenger?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-3"
                                                        title="Remove">
                                                        <i class="material-icons" style="font-size: 18px;">delete</i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center p-5">
                                    <p class="text-secondary">No passengers added yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Add Guest Form -->
                <div class="col-lg-5">
                    <div class="card border-0 rounded-4 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom p-4">
                            <h5 class="fw-bold m-0">Add New Guest</h5>
                        </div>
                        <div class="card-body p-4">
                            @if(session('success'))
                                <div class="alert alert-success rounded-3 mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger rounded-3 mb-4">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger rounded-3 mb-4">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($booking->passengers->count() < $booking->pax_count)
                                <form action="{{ route('booking.guests.store', $booking->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-secondary small text-uppercase fw-bold">Full Name (as
                                            per Passport/ID)</label>
                                        <input type="text" name="name" class="form-control rounded-3 py-2" required
                                            placeholder="e.g. John Doe">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-secondary small text-uppercase fw-bold">Passport
                                            Number</label>
                                        <input type="text" name="passport_number" class="form-control rounded-3 py-2"
                                            placeholder="Optional">
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label text-secondary small text-uppercase fw-bold">Date of
                                                Birth</label>
                                            <input type="date" name="date_of_birth" class="form-control rounded-3 py-2">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label
                                                class="form-label text-secondary small text-uppercase fw-bold">Gender</label>
                                            <select name="gender" class="form-select rounded-3 py-2">
                                                <option value="">Select...</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-danger w-100 py-3 rounded-3 fw-bold shadow-sm"
                                        style="background-color: #BC002D; border: none;">
                                        Add Guest
                                    </button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <div class="mb-3">
                                        <i class="material-icons text-success" style="font-size: 48px;">check_circle</i>
                                    </div>
                                    <h5 class="fw-bold">All Guests Added</h5>
                                    <p class="text-secondary small">You have reached the maximum number of guests
                                        ({{ $booking->pax_count }}) for this booking.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-secondary small">Need to change the number of guests? Please contact our support.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('partials.script')

</body>

</html>