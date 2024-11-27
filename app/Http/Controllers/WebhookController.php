<?php

namespace App\Http\Controllers;

use App\Models\Vendor\Subscription;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handleXenditWebhook(Request $request)
    {
        $payload = $request->all();

        if (isset($payload['status'])) {
            $subscription = Subscription::where('external_id', $payload['external_id'])->first();

            if ($subscription) {
                if ($payload['status'] === 'PAID') {
                    $subscription->update(['status' => 'active']);

                    // Tambahkan logika untuk lisensi jika diperlukan
                    $subscription->license->update([
                        'expires_at' => now()->addMonth(),
                    ]);
                } elseif ($payload['status'] === 'EXPIRED') {
                    $subscription->update(['status' => 'cancelled']);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
