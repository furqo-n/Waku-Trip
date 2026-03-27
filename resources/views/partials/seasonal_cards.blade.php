@forelse($seasonalTours as $index => $tour)
    <div class="col-md-4">
        <a href="{{ route('tour.show', $tour->slug) }}" class="text-decoration-none">
            <div class="season-card">
                <div class="season-bg"
                    style="background-image:url('{{ $tour->primary_image_url }}'); content-visibility: auto;">
                </div>
                <div class="season-overlay"></div>

                @if($index === 0)
                    <span class="badge bg-season-accent position-absolute top-0 start-0 m-4">
                        Top Rated
                    </span>
                @endif

                <div class="season-content">
                    <h3 class="text-white fw-bold">{{ $tour->title }}</h3>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column">
                            <span class="text-white fw-bold">{{ convert_currency($tour->base_price) }}</span>
                            <span class="text-white-50 small">{{ $tour->duration_days }}
                                {{ $tour->duration_days == 1 ? 'Day' : 'Days' }}</span>
                        </div>
                        <div class="icon-circle">
                            <span class="material-symbols-outlined">arrow_outward</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
@empty
    <div class="col-12 text-center py-5">
        <p class="text-muted">No {{ $currentSeason }} tours available at the moment. Check back soon!</p>
        <a href="{{ route('planned.index') }}" class="btn btn-outline-season rounded-pill px-4 py-2 mt-3">
            Browse All Tours
        </a>
    </div>
@endforelse