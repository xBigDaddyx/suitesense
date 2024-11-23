<?php

namespace App\Console\Commands;

use App\Models\Vendor\Plan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class GenerateLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suitify:generate-license {plan_id} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    public static function generateLicenseKey($segments = 6, $segmentLength = 6)
    {
        $key = '';
        $rawKey = '';

        for ($i = 0; $i < $segments; $i++) {
            $segment = strtoupper(bin2hex(random_bytes($segmentLength / 2)));
            $rawKey .= $segment;
            $key .= $segment;

            if ($i < $segments - 1) {
                $key .= '-';
            }
        }

        // Generate checksum (ambil 2 digit terakhir dari hash SHA-256)
        $checksum = substr(hash('sha256', $rawKey), -4);
        $key .= '-' . strtoupper($checksum);

        return $key;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $plan = Plan::findOrFail($this->argument('plan_id'));

        $plan->licenses()->create([
            'customer_id' =>
            'key' => $this->generateLicenseKey(),
            'type' => $this->argument('type'),
            'is_active' => true,
            'expires_at' => Carbon::now()->addDays(30),
        ]);
    }
}
