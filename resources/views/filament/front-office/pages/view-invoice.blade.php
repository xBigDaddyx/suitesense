<x-layouts.print title="Invoice">
    @php
        $hotel = auth()->user()->latestHotel;
    @endphp
    <div class="invoice max-w-4xl mx-auto bg-white overflow-hidden p-6 lg:p-10">
        <!-- Watermark -->
        <div class="watermark">PAID</div>
        <!-- Header Section -->
        <header class="flex justify-between items-center pb-4 border-b border-gray-200 mb-6">
            <!-- Company Info -->
            <div class="flex items-center space-x-4">
                <img src="logo.png" alt="Company Logo" class="h-12">
                <div>
                    <h1 class="text-xl font-bold tracking-wide text-gray-900">{{ $hotel->name }}</h1>
                    <p class="text-sm text-gray-500">{{ $hotel->address }}</p>
                    <p class="text-sm text-gray-500">Email: {{ $hotel->email }} | Phone: {{ $hotel->phone }}</p>
                </div>
            </div>

            <!-- Invoice Info -->
            <div class="text-right">
                <h2 class="text-2xl font-semibold text-gray-900">Invoice</h2>
                <p class="text-sm text-gray-500">Invoice #: {{ $record->number }}</p>
                <p class="text-sm text-gray-500">Date:
                    {{ \Carbon\Carbon::parse($record->created_at)->format('F j, Y') }}</p>
                <p class="text-sm text-gray-500">Payment Due:
                    {{ \Carbon\Carbon::parse($record->reservation->check_out)->format('F j, Y') }}</p>
            </div>
        </header>

        <!-- Client Information -->
        <section class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Bill To:</h3>
            <p class="text-sm text-gray-600 mt-1">{{ $record->reservation->guest->name }}</p>
            <p class="text-sm text-gray-600">{{ $record->reservation->guest->address }}</p>
            <p class="text-sm text-gray-600">Email: {{ $record->reservation->guest->email }} | Phone:
                {{ $record->reservation->guest->phone }}
            </p>
        </section>

        <!-- Main Content -->
        <main>
            <table class="w-full border-collapse mb-8">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border-b border-gray-200 py-2 px-3 text-left text-sm font-semibold">Description</th>
                        <th class="border-b border-gray-200 py-2 px-3 text-center text-sm font-semibold">Quantity</th>
                        <th class="border-b border-gray-200 py-2 px-3 text-center text-sm font-semibold">Unit Price</th>
                        <th class="border-b border-gray-200 py-2 px-3 text-right text-sm font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-b border-gray-200 py-2 px-3 text-sm text-gray-800">
                            {{ $record->reservation->number . ' (' . $record->reservation->room->roomType->name . ')' . '-' . $record->reservation->room->name }}
                        </td>
                        <td class="border-b border-gray-200 py-2 px-3 text-center text-sm text-gray-800">
                            {{ $record->reservation->total_nights }}
                        </td>
                        <td class="border-b border-gray-200 py-2 px-3 text-center text-sm text-gray-800">
                            {{ $record->reservation->room->price }}</td>
                        <td class="border-b border-gray-200 py-2 px-3 text-right text-sm text-gray-800">
                            {{ trans('frontOffice.room.pricePrefix') . $record->reservation->room->price * $record->reservation->total_nights }}
                        </td>

                    </tr>
                    @foreach ($record->reservation->additionalFacilities as $key => $item)
                        <tr>
                            <td class="border-b border-gray-200 py-2 px-3 text-sm text-gray-800">
                                {{ $item->facility->name . ' (' . $item->facility->description . ')' }}</td>
                            <td class="border-b border-gray-200 py-2 px-3 text-center text-sm text-gray-800">
                                {{ $item->quantity }}</td>
                            <td class="border-b border-gray-200 py-2 px-3 text-center text-sm text-gray-800">
                                {{ $item->facility->price }}</td>
                            <td class="border-b border-gray-200 py-2 px-3 text-right text-sm text-gray-800">
                                {{ trans('frontOffice.room.pricePrefix') . $item->facility->price * $item->quantity * $record->reservation->total_nights }}
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <div class="mt-8">
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex justify-between py-2 text-sm text-gray-700">
                        <span>Subtotal:</span>
                        <span
                            class="font-medium text-gray-900">{{ trans('frontOffice.room.pricePrefix') . $record->reservation->price }}</span>
                    </div>
                    <div class="flex justify-between py-2 text-sm text-gray-700">
                        <span>Tax (11%):</span>
                        <span
                            class="font-medium text-gray-900">{{ trans('frontOffice.room.pricePrefix') . number_format($record->reservation->price * 0.11, 2) }}</span>
                    </div>
                    <div
                        class="flex justify-between py-2 text-lg font-bold text-gray-900 border-t border-gray-300 mt-4 pt-2">
                        <span>Total:</span>
                        <span>{{ trans('frontOffice.room.pricePrefix') . ' ' . number_format($record->reservation->price + $record->reservation->price * 0.11, 2) }}</span>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer Section -->
        <footer class="mt-2 pt-4 border-t border-gray-200">
            <p class="text-sm text-gray-600 mb-2">Payment Instructions:</p>
            <ul class="text-sm text-gray-600 list-disc list-inside">
                <li>Bank Transfer: Account #123456789, Bank Name</li>
                <li>PayPal: paypal@example.com</li>
            </ul>
            <p class="text-sm text-gray-600 mt-4">Additional Notes: Thank you for your business!</p>
            <p class="text-center text-sm font-semibold text-gray-900 mt-6">We appreciate your prompt payment!</p>
        </footer>
    </div>
</x-layouts.print>
