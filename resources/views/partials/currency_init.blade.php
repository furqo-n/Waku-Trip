@php
    $currencyService = app(\App\Services\CurrencyService::class);
    $currentCode = $currencyService->getCurrentCurrency();
    $details = $currencyService->getCurrencyDetails($currentCode);
    $rate = $details->rate ?? 1.0;
    $formatStr = $details->format ?? '%s';
@endphp
<script>
    window.WakuCurrency = {
        code: "{{ $currentCode }}",
        rate: {{ $rate }},
        formatStr: "{{ $formatStr }}",
        format: function(amount) {
            const converted = amount * this.rate;
            const localeMap = { 'IDR': 'id-ID', 'JPY': 'ja-JP' };
            const isZeroDecimal = ['IDR', 'JPY'].includes(this.code);
            
            const formatted = converted.toLocaleString(localeMap[this.code] || 'en-US', {
                minimumFractionDigits: isZeroDecimal ? 0 : 2,
                maximumFractionDigits: isZeroDecimal ? 0 : 2
            });

            return this.formatStr.replace('%s', formatted);
        }
    };
</script>
