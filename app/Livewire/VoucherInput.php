<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\VoucherService;

class VoucherInput extends Component
{
    public string $code = '';
    public ?array $voucher = null;
    public string $error = '';
    public bool $isValid = false;
    public float $orderAmount = 0;

    protected VoucherService $voucherService;

    public function boot(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    public function updatedCode($value)
    {
        if (empty($value)) {
            $this->reset(['voucher', 'error', 'isValid']);
            return;
        }

        $this->validateVoucher();
    }

    public function validateVoucher()
    {
        if (!$this->code) {
            return;
        }

        $userId = auth()->id();
        
        $result = $this->voucherService->validate($this->code, $userId, $this->orderAmount);

        if ($result['valid']) {
            $this->voucher = [
                'id' => $result['voucher']->id,
                'code' => $result['voucher']->code,
                'title' => $result['voucher']->title,
                'type' => $result['voucher']->type,
                'value' => $result['voucher']->value,
                'max_discount' => $result['voucher']->max_discount,
                'min_order_amount' => $result['voucher']->min_order_amount,
            ];
            $this->error = '';
            $this->isValid = true;
        } else {
            $this->voucher = null;
            $this->error = $result['error'];
            $this->isValid = false;
        }
    }

    public function applyVoucher()
    {
        if (!$this->isValid || !$this->voucher) {
            return;
        }

        $this->dispatch('voucherApplied', [
            'code' => $this->code,
            'voucher' => $this->voucher,
        ]);
    }

    public function clearVoucher()
    {
        $this->reset(['code', 'voucher', 'error', 'isValid']);
        
        $this->dispatch('voucherCleared');
    }

    public function render()
    {
        return view('livewire.voucher-input');
    }
}