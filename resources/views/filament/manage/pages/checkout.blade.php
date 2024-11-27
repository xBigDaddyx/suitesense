<x-filament::page>
    <h1>Checkout</h1>
    <p>Order ID: {{ 'ORDER-' . $plan->number }}</p>
    <p>Total Pembayaran: Rp {{ number_format($plan->price, 0, ',', '.') }}</p>

    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript" src="<https://app.sandbox.midtrans.com/snap/snap.js>"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil!");
                    console.log(result);
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran!");
                    console.log(result);
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    console.log(result);
                },
                onClose: function() {
                    alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                }
            });
        });
    </script>
</x-filament::page>
