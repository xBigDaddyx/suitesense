<?php

namespace App\Filament\Vendor\Clusters\Product\Resources\PlanResource\Pages;

use App\Filament\Vendor\Clusters\Product\Resources\PlanResource;
use App\Models\Vendor\Order;
use App\Models\Vendor\Plan;
use App\Models\Vendor\Subscription;
use Carbon\Carbon;
use Exception;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use GlennRaya\Xendivel\Xendivel;
use Xendit\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Midtrans\Config;
use Midtrans\Snap;
use Xendit\Invoice\InvoiceApi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\RawJs;
use Illuminate\Support\HtmlString;

class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;
    protected static string $view = 'filament.manage.pages.subscription-list';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function processPayment($request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Mendapatkan data order dari database
        $data = Plan::find($request);

        // Parameter yang dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'ORD-' . Carbon::now()->format('H-i') . '-'  . $data->number,
                'gross_amount' => $data->price,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
            ],
            'item_details' => [
                [
                    'id' => $data->number,
                    'price' => $data->price,
                    'quantity' => 1,
                    'name' => $data->name,
                ],
            ],
        ];

        try {

            $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
            $order = new Order([
                'transaction_details' => $params['transaction_details'],
                'customer_details' => $params['customer_details'],
                'item_details' => $params['item_details'],
                'status' => 'pending',
            ]);
            $order->save();
            // Redirect to Snap Payment Page
            // return redirect($paymentUrl);
            // Mendapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // Menampilkan halaman checkout dengan Snap Token
            //return redirect()->route('filament.manage.pages.checkout.{snapToken}.{plan}', ['tenant' => Filament::getTenant(), 'snapToken' => $snapToken, 'plan' => $data]);
            // return view('filament.vendor.pages.checkout', ['snapToken' => $snapToken, 'plan' => $data]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
    public function createInvoice($data)
    {
        $data = Plan::find($data);
        $request = new Collection([
            'amount' => 1750000,
            'currency' => 'IDR',
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code' => 'PH_GCASH',
            'channel_properties' => [
                'success_redirect_url' => 'https://your-domain.test/ewallet/success',
                'failure_redirect_url' => 'https://your-domain.test/ewallet/failed',
            ],
        ]);

        $response = Xendivel::payWithEwallet($request)
            ->getResponse();

        dd($response);
        // $apiInstance = new InvoiceApi();
        // $createInvoice = new \Xendit\Invoice\CreateInvoiceRequest([
        //     'external_id' => $data->number,
        //     'amount' => $data->price,
        //     'description' => $data->name,
        //     'invoice_duration' => 172800,
        //     'currency' => $data->currency,
        //     'reminder_time' => 1,
        //     'customer' => array(
        //         'given_name' => auth()->user()->name,
        //         'email' => auth()->user()->email,
        //     ),
        //     'success_redirect_url' => 'http://localhost:8000',
        //     'failure_redirect_url' => 'http://localhost:8000',
        // ]);

        // $result = $apiInstance->createInvoice($createInvoice);

        $subscription = new Subscription([
            'plan_id' => $data->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays($data->duration_in_days),
            'is_active' => true,
            'customer_id' => auth()->user()->id,
            'hotel_id' => auth()->user()->latestHotel->id,
            'payment_method_id' => $data->number,
        ]);
        $subscription->save();
    }
    public function subscribeAction(): Action
    {
        return Action::make('subscribe')
            ->label('Subscribe now')
            ->icon('tabler-check')
            ->color('primary')
            ->openUrlInNewTab()
            ->action(function (array $arguments) {
                redirect()->route('cart', ['plan' => $arguments['plan']]);
            });
    }
    public function deleteAction(): Action
    {
        return Action::make('delete')

            ->label('Delete plan')
            ->color('danger')
            ->icon('tabler-trash')
            ->requiresConfirmation()
            ->action(function (array $arguments) {
                $post = Plan::find($arguments['plan']);
                $post?->delete();
            });
    }
    public function editAction(): Action
    {
        return Action::make('edit')
            ->label('Edit plan')
            ->color('gray')
            ->icon('tabler-pencil')
            ->url(fn(array $arguments) => Filament::getResourceUrl(Plan::class, 'edit', ['record' => $arguments['plan']]));
    }
}
