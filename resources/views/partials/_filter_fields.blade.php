<!-- Trip Format Filter -->
<div class="mb-4">
    <h6 class="fw-bold text-uppercase text-secondary small mb-3 opacity-75"
        style="letter-spacing: 0.1em;">Trip Format</h6>
    <div class="d-flex flex-column gap-2">
        <label
            class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-white shadow-sm w-100 mb-0"
            style="cursor: pointer; border: 1px solid rgba(0,0,0,0.08);">
            <span class="d-flex align-items-center gap-2 text-dark">
                <input type="checkbox" 
                       name="trip_types[]" 
                       value="open"
                       style="width: 18px; height: 18px; accent-color: #BC002D; flex-shrink: 0;"
                       {{ in_array('open', $selectedTripTypes) ? 'checked' : '' }}>
                <span class="fw-bold small">Open Group</span>
            </span>
            <span class="material-symbols-outlined text-secondary fs-5 opacity-50">groups</span>
        </label>
        <label
            class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-white shadow-sm w-100 mb-0"
            style="cursor: pointer; border: 1px solid rgba(0,0,0,0.08);">
            <span class="d-flex align-items-center gap-2 text-dark">
                <input type="checkbox" 
                       name="trip_types[]" 
                       value="private"
                       style="width: 18px; height: 18px; accent-color: #BC002D; flex-shrink: 0;"
                       {{ in_array('private', $selectedTripTypes) ? 'checked' : '' }}>
                <span class="fw-bold small">Private Tour</span>
            </span>
            <span class="material-symbols-outlined text-secondary fs-5 opacity-50">person</span>
        </label>
    </div>
</div>

<!-- Categories Filter -->
<div class="mb-4">
    <h6 class="fw-bold text-uppercase text-secondary small mb-3 opacity-75"
        style="letter-spacing: 0.1em;">Categories</h6>
    <div class="d-flex flex-column gap-2" style="max-height: 300px; overflow-y: auto;">
        @foreach($categories as $category)
        <label
            class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-white shadow-sm w-100 mb-0" 
            style="cursor: pointer; border: 1px solid rgba(0,0,0,0.08);">
            <span class="d-flex align-items-center gap-2 text-dark">
                <input type="checkbox" 
                       name="categories[]" 
                       value="{{ $category->id }}"
                       style="width: 18px; height: 18px; accent-color: #BC002D; flex-shrink: 0;"
                       {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                <span class="fw-bold small">{{ $category->name }}</span>
            </span>
            <span class="material-symbols-outlined text-secondary fs-6 opacity-50">
                {{ $category->icon ?? 'category' }}
            </span>
        </label>
        @endforeach
    </div>
</div>

<!-- Season Filter -->
<div class="mb-4">
    <h6 class="fw-bold text-uppercase text-secondary small mb-3 opacity-75"
        style="letter-spacing: 0.1em;">Season</h6>
    <div class="d-flex flex-column gap-2">
        @php
            $seasons = ['Spring', 'Summer', 'Autumn', 'Winter'];
            $seasonIcons = [
                'Spring' => 'spa',
                'Summer' => 'wb_sunny',
                'Autumn' => 'eco',
                'Winter' => 'ac_unit'
            ];
        @endphp
        @foreach($seasons as $season)
        <label
            class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-white shadow-sm w-100 mb-0"
            style="cursor: pointer; border: 1px solid rgba(0,0,0,0.08);">
            <span class="d-flex align-items-center gap-2 text-dark">
                <input type="checkbox" 
                       name="seasons[]" 
                       value="{{ $season }}"
                       style="width: 18px; height: 18px; accent-color: #BC002D; flex-shrink: 0;"
                       {{ isset($selectedSeasons) && in_array($season, $selectedSeasons) ? 'checked' : '' }}>
                <span class="fw-bold small">{{ $season }}</span>
            </span>
            <span class="material-symbols-outlined text-secondary fs-5 opacity-50">{{ $seasonIcons[$season] }}</span>
        </label>
        @endforeach
    </div>
</div>

<!-- Budget Range Filter -->
<div class="mb-4">
    <h6 class="fw-bold text-uppercase text-secondary small mb-3 opacity-75"
        style="letter-spacing: 0.1em;">Budget Range</h6>
    
    <script>
        if (typeof window.dailyBudgetRate === 'undefined') {
            window.dailyBudgetRate = {{ config("currency.currencies.{$currentCurrency}.rate", 1) }};
            window.dailyBudgetSymbol = "{{ $currencySymbol }}";
            window.dailyBudgetCode = "{{ $currentCurrency }}";
            
            window.formatBudgetDisplay = function(usdAmount) {
                let converted = usdAmount * window.dailyBudgetRate;
                let formatted;
                
                if (window.dailyBudgetCode === 'IDR' || window.dailyBudgetCode === 'JPY') {
                     formatted = Math.round(converted).toLocaleString('en-US');
                } else {
                     formatted = converted.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
                }
                return window.dailyBudgetSymbol + ' ' + formatted;
            }
        }
    </script>
    
    <div class="mb-3">
        <input type="range" class="form-range" name="max_price" id="priceRange" 
               min="100" max="20000" step="100" value="{{ $maxPrice }}"
               oninput="document.getElementById('priceValue').innerText = window.formatBudgetDisplay(this.value)">
        <input type="hidden" name="min_price" value="100">
    </div>
    <div class="d-flex justify-content-between fw-black text-secondary small">
        <span>{{ convert_currency(100) }}</span>
        <span id="priceValue">{{ convert_currency($maxPrice) }}</span>
    </div>
</div>
