<?php

namespace App\Livewire;

use App\Models\Vendor\Plan;
use Carbon\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use App\Models\Vendor\Cart as CartModel;
use App\Models\Vendor\Order;
use Exception;
use Livewire\Attributes\Title;
use Midtrans\Config;
use Midtrans\Snap;

class Cart extends Component
{

    public Plan $plan;
    public $cart;
    public int $duration = 1;
    public $snapToken;

    public $cartData = [
        'hargaAwal' => null,
        'persentaseDiskon' => null,
        'hargaDiskon' => null,
        'totalHemat' => null,
        'totalHarga' => null,
        'expiredDate' => '',
    ];

    public function mount($plan)
    {


        $this->getCart();
        if (!empty($this->cart)) {
            $this->duration = $this->cart->duration;
            $this->cartData['hargaAwal'] = $this->plan->price; // Harga awal sebelum diskon
            $this->cartData['hargaDiskon'] = $this->cart['item_details']['price']; // Harga setelah diskon
            $this->cartData['expiredDate'] = Carbon::parse($this->cart->end_date)->format('d/m/Y'); // Tanggal expired

        } else {
            $this->duration = 1;
            $this->cartData['hargaAwal'] = $plan->price; // Harga awal sebelum diskon
            $this->cartData['expiredDate'] = Carbon::now()->addMonths(1)->format('d/m/Y'); // Tanggal expired

        }
        $this->calculate();
        $this->fetchRecord($plan);
    }

    #[Computed]
    public function getCart()
    {
        return $this->cart = CartModel::where('created_by', auth()->user()->id)->first() ?? '';
    }
    public function calculate()
    {
        $duration = $this->duration;
        $this->cartData['totalHarga'] = $this->cartData['hargaAwal']; // Total harga
        switch ($duration) {
            case 1:
                $this->cartData['expiredDate'] = Carbon::now()->addMonths(1)->format('d/m/Y'); // Tanggal expired
                $this->cartData['persentaseDiskon'] = 0; // Diskon 0%
                $this->cartData['hargaDiskon'] = $this->plan->price; // Total hemat

                break;
            case 3:
                $this->cartData['persentaseDiskon'] = 10 / 100; // Diskon 10% (ubah ke desimal)
                $this->cartData['expiredDate'] = Carbon::now()->addMonths(3)->format('d/m/Y'); // Tanggal expired
                break;
            case 6:
                $this->cartData['persentaseDiskon'] = 20 / 100; // Diskon 20% (ubah ke desimal)
                $this->cartData['expiredDate'] = Carbon::now()->addMonths(6)->format('d/m/Y'); // Tanggal expired
                break;
            case 12:
                $this->cartData['persentaseDiskon'] = 30 / 100; // Diskon 30% (ubah ke desimal)
                $this->cartData['expiredDate'] = Carbon::now()->addMonths(12)->format('d/m/Y'); // Tanggal expired
                break;
            case 24:
                $this->cartData['persentaseDiskon'] = 40 / 100; // Diskon 40% (ubah ke desimal)
                $this->cartData['expiredDate'] = Carbon::now()->addMonths(24)->format('d/m/Y'); // Tanggal expired
                break;
        }
        $this->cartData['hargaDiskon'] = $this->cartData['hargaAwal'] * (1 - $this->cartData['persentaseDiskon']); // Harga setelah diskon
        $this->cartData['totalHemat'] = $this->cartData['hargaAwal'] * $this->cartData['persentaseDiskon']; // Total hemat
        $this->cartData['totalHarga'] = $this->cartData['hargaDiskon'] * $duration; // Total harga
    }
    #[Computed]
    public function updated($property, $value)
    {
        // $property: The name of the current property being updated
        // $value: The value about to be set to the property

        if ($property === 'duration') {
            $this->calculate();
        }
    }
    public function saveCart()
    {
        $getCart = $this->cart;

        $this->cart = CartModel::updateOrCreate(['created_by' => auth()->user()->id], [
            'item_details' => [
                'id' => $this->plan->id,
                'name' => $this->plan->name,
                'price' => $this->cartData['hargaDiskon'],
                'quantity' => $this->duration,
            ],
            'total_details' => [
                'total' => $this->cartData['totalHarga'],
                'discount' => $this->cartData['totalHemat'],
                'percentageDiscount' => $this->cartData['persentaseDiskon'],
            ],
            'duration' => $this->duration,
            'start_date' => now()->format('Y-m-d'),
            'end_date' => Carbon::createFromFormat('d/m/Y', $this->cartData['expiredDate'])->format('Y-m-d'),
        ]);
    }
    #[Computed]
    public function fetchRecord($plan)
    {
        return $this->plan = $plan;
    }
    public function processPayment()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        $this->saveCart();

        // Mendapatkan data order dari database
        $plan = $this->plan;
        $cart = $this->cart;
        // Parameter yang dikirim ke Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $cart->number,
                'gross_amount' => $cart['total_details']['total'],
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
            ],
            // 'items' => $cart['item_details'],
            'item_details' => [
                [
                    'id' => $cart['item_details']['id'],
                    'price' => $cart['item_details']['price'],
                    'quantity' => $cart['item_details']['quantity'],
                    'name' => $cart['item_details']['name'],
                ],
            ],
        ];

        try {

            // $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
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
            $this->snapToken = Snap::getSnapToken($params);
            $this->dispatch('span-token-generated', token: $this->snapToken);
            // Menampilkan halaman checkout dengan Snap Token
            //return redirect()->route('filament.manage.pages.checkout.{snapToken}.{plan}', ['tenant' => Filament::getTenant(), 'snapToken' => $snapToken, 'plan' => $data]);
            // return view('filament.vendor.pages.checkout', ['snapToken' => $snapToken, 'plan' => $data]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
    public function success() {}

    #[Title('Cart')]
    public function render()
    {
        return view('livewire.cart');
    }
}
