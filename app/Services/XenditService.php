<?php

namespace App\Services;

use Xendit\Xendit;

class XenditService
{
    public function __construct()
    {
        Xendit::setApiKey(config('services.xendit.api_key'));
    }

    public function createRecurringPayment($customerId, $price, $interval, $currency = 'IDR')
    {
        $params = [
            'external_id' => 'subscription-' . $customerId . '-' . now()->timestamp,
            'amount' => $price,
            'payer_email' => 'customer@example.com', // Update dengan email customer
            'description' => 'Subscription Payment',
            'interval' => $interval, // 'DAILY', 'WEEKLY', 'MONTHLY', 'ANNUALLY'
            'currency' => $currency,
            'start_date' => now()->toIso8601String(),
        ];

        return \Xendit\Recurring::create($params);
    }
}
