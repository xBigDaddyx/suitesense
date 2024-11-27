<main class="flex-grow flex items-center justify-center px-4 py-12" x-data="{
    pay() {
        $wire.processPayment();
    }
}"
    x-on:span-token-generated.window="snap.pay($event.detail.token, {
    onSuccess: result => {
        $wire.success()
    },
    onPending: result =>{

    },
    onError: result => {

    }
})">
    <div class="grid w-full max-w-6xl grid-cols-1 gap-6 md:grid-cols-3" data-barba="container" data-barba-namespace="cart">
        <!-- Left Card -->
        <div class="md:col-span-2 bg-white rounded-lg shadow-lg p-6 ">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $plan->name }} Plan</h1>
            <p class="text-gray-600 mb-6">{{ $plan->description }}</p>
            <hr class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="w-full md:w-auto mb-4 md:mb-0">
                    <label for="duration" class="block text-sm font-medium mb-2 dark:text-white">Duration</label>
                    <select id="duration" wire:model.live="duration"
                        class="py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-primary-500 focus:ring-primary-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                        <option value="1" selected>1 Month</option>
                        <option value="3">3 Month</option>
                        <option value="6">6 Month</option>
                        <option value="12">12 Month</option>
                        <option value="24">24 Month</option>
                    </select>
                </div>
                <div class="flex flex-col md:flex-row md:items-center md:space-x-4">
                    @if ($duration > 1)
                        <span class="bg-red-500 text-white text-sm font-medium px-3 py-1 rounded-full mb-2 md:mb-0">

                            Hemat
                            {{ $plan->currency . ' ' . number_format($cartData['totalHemat'], 0, ',', '.') . '/month' }}
                        </span>
                    @endif

                    <div class="text-right">

                        <p class="text-xl font-bold text-primary-600">
                            {{ $plan->currency . ' ' . number_format($cartData['hargaDiskon'], 0, ',', '.') . '/month' }}
                        </p>
                        @if ($duration > 1)
                            <p class="text-sm text-gray-500 line-through">
                                {{ $plan->currency . ' ' . number_format($cartData['hargaAwal'], 0, ',', '.') . '/month' }}
                            </p>
                        @endif

                    </div>
                </div>
            </div>
            @if ($duration > 1)
                <p class="mt-16 text-sm text-gray-600">
                    Renewal fee
                    <span
                        class="font-bold">{{ $plan->currency . ' ' . number_format($plan->price, 0, ',', '.') . '/month' }}</span>
                    on <span class="font-bold">{{ $cartData['expiredDate'] }}</span>. Can be canceled or not renewed at
                    any
                    time
                    at any time!
                </p>
                <div class="mt-2 bg-teal-100 border border-teal-200 text-sm text-teal-800 rounded-lg p-4 dark:bg-teal-800/10 dark:border-teal-900 dark:text-teal-500"
                    role="alert" tabindex="-1" aria-labelledby="announcement-alert">
                    <p id="announcement-alert">
                        Congratulation! You get a discount of <span
                            class="font-bold">-{{ $cartData['persentaseDiskon'] * 100 }}%</span>
                        on
                        your first purchase.
                        <i class="fas fa-info-circle ml-1"></i>
                    </p>

                </div>
            @endif


        </div>

        <!-- Right Card -->
        <div class="bg-white rounded-lg shadow-lg p-6 ">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Order Summary</h2>
            <div class="flex justify-between mb-2">
                <p class="text-gray-600">Subtotal</p>
                <p @class([
                    'text-gray-600',
                    'text-gray-600 line-through' => $duration > 1,
                ])>
                    {{ $plan->currency . ' ' . number_format($cartData['hargaAwal'], 0, ',', '.') }}

            </div>
            <div class="flex
                justify-between mb-2">
                @if ($duration > 1)
                    <p class="text-gray-600">Diskon Paket -{{ $cartData['persentaseDiskon'] * 100 }}%</p>
                    <p class="text-primary-600">
                        {{ $plan->currency . ' ' . number_format($cartData['hargaDiskon'], 0, ',', '.') }}</p>
                @endif


            </div>
            <hr class="my-4">
            <div class="flex justify-between mb-6">
                <p class="text-lg font-bold text-gray-800">Total</p>
                <p class="text-lg font-bold text-gray-800">
                    {{ $plan->currency . ' ' . number_format($cartData['totalHarga'] ?? $plan->price, 0, ',', '.') }}
                </p>
            </div>
            <div class="mb-6">

                <button x-on:click="pay" id="pay-button"
                    class="w-full bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-3 rounded-md transition duration-300">
                    <span wire:loading wire:target="processPayment"
                        class="animate-spin inline-block size-4 border-[3px] border-current border-t-transparent text-white rounded-full"
                        role="status" aria-label="loading">
                        <span class="sr-only">Loading...</span>
                    </span>
                    <div wire:loading.remove>Checkout</div>
                </button>
            </div>
            {{-- <div class="text-center">
            <a href="#" class="text-sm text-primary-600 hover:underline">Punya Kode Kupon?</a>
        </div> --}}
        </div>
    </div>



    @assets
        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endassets
</main>
