<?php

namespace App\Console\Commands;

use App\Models\Vendor\License;
use App\Services\XenditService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoRenewalLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suitify:auto-renewal-license';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();
        $licenses = License::where('expires_at', '<=', $today->addDay())
            ->where('is_active', true)
            ->get();

        foreach ($licenses as $license) {
            $subscription = $license->hotel->subscription;

            if ($subscription && $subscription->status === 'active') {
                try {
                    // Memanggil Xendit API untuk pembayaran otomatis
                    $paymentResult = app(XenditService::class)
                        ->createRecurringPayment(
                            $subscription->customer_id,
                            $subscription->plan->price,
                            'MONTHLY'
                        );

                    // Periksa respons pembayaran
                    if ($paymentResult['status'] === 'ACTIVE') {
                        $license->update([
                            'expires_at' => $license->expires_at->addMonth(),
                        ]);

                        $subscription->update([
                            'renewal_date' => $subscription->renewal_date->addMonth(),
                        ]);

                        $this->info("License {$license->key} renewed successfully with Xendit.");
                    } else {
                        $this->warn("Failed to renew License {$license->key}: {$paymentResult['status']}");
                    }
                } catch (\Exception $e) {
                    $this->error("Error renewing License {$license->key}: " . $e->getMessage());
                }
            } else {
                $license->update(['is_active' => false]);
                $this->warn("License {$license->key} could not be renewed.");
            }
        }

        $this->info('Auto-renewal process completed.');
    }
}
