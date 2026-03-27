<div class="voucher-input-component">
    <div class="mb-3">
        <label for="voucher_code" class="form-label">Voucher Code</label>
        <div class="input-group">
            <input 
                type="text" 
                id="voucher_code"
                wire:model="code"
                wire:input="validateVoucher"
                class="form-control"
                placeholder="Enter voucher code"
                {{ !$this->isValid && !$this->voucher ? '' : 'disabled' }}
            >
            @if($this->isValid && $this->voucher)
                <button type="button" wire:click="clearVoucher" class="btn btn-outline-danger">
                    Clear
                </button>
            @endif
        </div>
        @if($this->error)
            <div class="text-danger mt-1 small">{{ $this->error }}</div>
        @endif
    </div>

    @if($this->isValid && $this->voucher)
        <div class="alert alert-success">
            <strong>{{ $this->voucher['code'] }}</strong> - {{ $this->voucher['title'] }}
            <br>
            @if($this->voucher['type'] === 'percentage')
                <small>{{ $this->voucher['value'] }}% off @if($this->voucher['max_discount']) (Max: {{ Number::formatCurrency($this->voucher['max_discount']) }}) @endif</small>
            @elseif($this->voucher['type'] === 'fixed_amount')
                <small>{{ Number::formatCurrency($this->voucher['value']) }} off</small>
            @elseif($this->voucher['type'] === 'free_shipping')
                <small>Free Shipping</small>
            @endif
        </div>
    @endif
</div>