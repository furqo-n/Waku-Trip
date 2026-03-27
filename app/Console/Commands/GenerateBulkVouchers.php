<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateBulkVouchers extends Command
{
    protected $signature = 'vouchers:generate
                            {prefix : The prefix for voucher codes}
                            {count : Number of vouchers to generate}
                            {type : Voucher type (percentage, fixed_amount, free_shipping)}
                            {value : The voucher value (percentage or amount)}
                            {--expires= : Expiry date (Y-m-d)}';

    protected $description = 'Generate bulk voucher codes';

    public function handle(): int
    {
        $prefix = strtoupper($this->argument('prefix'));
        $count = (int) $this->argument('count');
        $type = $this->argument('type');
        $value = $this->argument('value');
        $expiresAt = $this->option('expires')
            ? \Carbon\Carbon::parse($this->option('expires'))
            : now()->addMonth();

        if ($count > 10000) {
            $this->error('Maximum 10000 vouchers can be generated at once.');
            return Command::FAILURE;
        }

        $this->info("Generating {$count} vouchers...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $vouchers = [];
        $generated = 0;

        while ($generated < $count) {
            $code = $prefix . strtoupper(Str::random(8));

            if (!Voucher::where('code', $code)->exists()) {
                $vouchers[] = [
                    'code' => $code,
                    'title' => 'Bulk Voucher',
                    'type' => $type,
                    'value' => $value,
                    'starts_at' => now(),
                    'expires_at' => $expiresAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $generated++;
                $bar->advance();
            }
        }

        foreach (array_chunk($vouchers, 1000) as $chunk) {
            Voucher::insert($chunk);
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully generated {$count} vouchers.");

        return Command::SUCCESS;
    }
}
