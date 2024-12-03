<x-layouts.modal title="Check Out">

    <!-- Main Content -->
    <main class="p-6 space-y-8">

        <!-- Guest Information -->
        <section>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Guest Information</h2>
            <div class="space-y-2 text-sm">
                <p><strong>Name:</strong> {{ $record->guest->name }}</p>
                <p><strong>Contact:</strong> {{ $record->guest->email }} | {{ $record->guest->phone }}</p>
            </div>
        </section>

        <!-- Room Details -->
        <section>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Room Details</h2>
            <div class="space-y-2 text-sm">
                <p><strong>Room Type:</strong> {{ $record->room->roomType->name }}</p>
                <p><strong>Room Number:</strong> {{ $record->room->name }}</p>
                <p><strong>Guests:</strong> {{ $record->guest->name }}</p>
                <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($record->check_in)->format('F j, Y') }}</p>
                <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($record->check_out)->format('F j, Y') }}</p>
                <p><strong>Length of Stay:</strong> {{ $record->total_nights }} nights</p>
                {{-- <p><strong>Room Number:</strong> 502</p>
                <p><strong>Guests:</strong> 2 Adults</p>
                <p><strong>Check-in:</strong> 2024-11-28 3:00 PM</p>
                <p><strong>Check-out:</strong> 2024-12-01 11:00 AM</p>
                <p><strong>Length of Stay:</strong> 3 nights</p> --}}
            </div>
        </section>

        <!-- Billing Breakdown -->
        <section>
            <!-- Watermark -->
            <div class="watermark">PAID</div>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Billing Breakdown</h2>
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
                            {{ $record->number . ' (' . $record->room->roomType->name . ')' . '-' . $record->room->name }}
                        </td>
                        <td class="border-b border-gray-200 py-2 px-3 text-center text-sm text-gray-800">
                            {{ $record->total_nights }}
                        </td>
                        <td class="border-b border-gray-200 py-2 px-3 text-center text-sm text-gray-800">
                            {{ $record->room->price }}</td>
                        <td class="border-b border-gray-200 py-2 px-3 text-right text-sm text-gray-800">
                            {{ trans('frontOffice.room.pricePrefix') . $record->room->price * $record->total_nights }}
                        </td>

                    </tr>
                    @foreach ($record->additionalFacilities as $key => $item)
                        <tr>
                            <td class="border-b border-gray-200 py-2 px-3 text-sm text-gray-800">
                                {{ $item->facility->name . ' (' . $item->facility->description . ')' }}</td>
                            <td class="border-b border-gray-200 py-2 px-3 text-center text-sm text-gray-800">
                                {{ $item->quantity }}</td>
                            <td class="border-b border-gray-200 py-2 px-3 text-center text-sm text-gray-800">
                                {{ $item->facility->price }}</td>
                            <td class="border-b border-gray-200 py-2 px-3 text-right text-sm text-gray-800">
                                {{ trans('frontOffice.room.pricePrefix') . $item->facility->price * $item->quantity * $record->total_nights }}
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
                            class="font-medium text-gray-900">{{ trans('frontOffice.room.pricePrefix') . $record->price }}</span>
                    </div>
                    <div class="flex justify-between py-2 text-sm text-gray-700">
                        <span>Tax (11%):</span>
                        <span
                            class="font-medium text-gray-900">{{ trans('frontOffice.room.pricePrefix') . number_format($record->price * 0.11, 2) }}</span>
                    </div>
                    <div
                        class="flex justify-between py-2 text-lg font-bold text-gray-900 border-t border-gray-300 mt-4 pt-2">
                        <span>Total:</span>
                        <span>{{ trans('frontOffice.room.pricePrefix') . ' ' . number_format($record->price + $record->price * 0.11, 2) }}</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- <!-- Payment Method -->
        <section>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Payment Method</h2>
            <div class="space-y-2">
                <label class="block">
                    <input type="radio" name="payment-method" class="mr-2" checked>
                    Credit Card (**** **** **** 1234)
                </label>
                <label class="block">
                    <input type="radio" name="payment-method" class="mr-2">
                    PayPal
                </label>
                <label class="block">
                    <input type="radio" name="payment-method" class="mr-2">
                    Cash
                </label>
            </div>
        </section>

        <!-- Additional Options -->
        <section>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Additional Options</h2>
            <label class="block">
                <input type="checkbox" class="mr-2">
                Request Late Check-Out (Additional $30.00)
            </label>
        </section> --}}

        {{-- <!-- Guest Feedback -->
        <section>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Feedback</h2>
            <textarea class="w-full border rounded-lg p-2 text-sm" rows="4" placeholder="Let us know about your stay..."></textarea>
        </section>

        <!-- Actions -->
        <section class="space-y-4">
            <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700">
                Confirm Check-Out
            </button>
            <button class="w-full bg-gray-100 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-200">
                Email Receipt
            </button>
        </section> --}}
    </main>

</x-layouts.modal>
