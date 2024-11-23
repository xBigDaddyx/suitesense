<?php

namespace App\Console\Commands;

use App\Models\Vendor\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchCountry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suitify:fetch-country';

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
        $response = Http::get('https://restcountries.com/v3.1/all');
        if ($response->successful()) {
            $countries = collect($response->json())->pluck('name.common')->sort()->values();
            foreach ($countries as $country) {
                Country::create([
                    'name' => $country,
                ]);
            }
        }
    }
}
