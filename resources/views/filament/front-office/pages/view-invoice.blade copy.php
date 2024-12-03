<x-layouts.app title="Invoice">
    <div class="bg-white flex-grow flex items-center justify-center px-4 py-12">
        <div class="max-w-5xl mx-auto bg-white rounded-lg overflow-hidden print-border border ">
            <!-- Watermark -->
            @if ($record->status === 'paid')
                <div class="absolute inset-0 flex items-center justify-center">
                    <p
                        class="text-primary-500 text-[200px] font-bold opacity-20 transform rotate-12 select-none pointer-events-none">
                        {{ trans('frontOffice.invoice.watermarkPaid') }}
                    </p>
                </div>
            @endif
            <!-- Header -->
            <div class="bg-primary-500 text-white py-6 px-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold">Invoice</h1>
                        <p class="text-sm">Invoice #: <span class="font-medium">{{ $record->number }}</span></p>
                        <p class="text-sm">Date:
                            {{ \Carbon\Carbon::parse($record->invoice_date)->format('F j, Y') }}
                        </p>
                    </div>
                    <div>
                        <img class="h-10" src="{{ asset('images/logo/suitify_logo_dark.svg') }}" alt="Logo">
                    </div>
                </div>
            </div>

            <!-- Sender and Receiver -->
            <div class="p-8 border-b border-gray-200">
                <div class="grid grid-cols-2 gap-12">
                    <!-- Billed To -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Billed To:</h2>
                        <p class="text-gray-600">{{ $record->reservation->guest->name }}</p>
                        <p class="text-gray-600">{{ $record->reservation->guest->address }}</p>
                        <p class="text-gray-600">Email: {{ $record->reservation->guest->email }}</p>
                    </div>
                    <!-- From -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">From:</h2>
                        <p class="text-gray-600">{{ $record->reservation->room->hotel->name }}</p>
                        <p class="text-gray-600">{{ $record->reservation->room->hotel->address }}</p>
                        <p class="text-gray-600">Email: support@hotelboyolali.com</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Items -->
            <div class="p-8">
                <table class="w-full text-left border">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-300">
                            <th class="px-6 py-4 text-sm font-medium text-gray-700">Description</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700 text-right">Quantity</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700 text-right">Unit Price</th>
                            <th class="px-6 py-4 text-sm font-medium text-gray-700 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-gray-800">
                                {{ $record->reservation->number . ' (' . $record->reservation->room->roomType->name . ')' . '-' . $record->reservation->room->name }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-800">{{ $record->reservation->total_nights }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-800">{{ $record->reservation->room->price }}</td>
                            <td class="px-6 py-4 text-right text-gray-900 font-semibold">
                                {{ trans('frontOffice.room.pricePrefix') . $record->reservation->room->price * $record->reservation->total_nights }}
                            </td>

                        </tr>
                        @foreach ($record->reservation->additionalFacilities as $key => $item)
                            <tr>
                                <td class="px-6 py-4 text-gray-800">
                                    {{ $item->facility->name . ' (' . $item->facility->description . ')' }}</td>
                                <td class="px-6 py-4 text-right text-gray-800">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-gray-800">{{ $item->facility->price }}</td>
                                <td class="px-6 py-4 text-right text-gray-900 font-semibold">
                                    {{ trans('frontOffice.room.pricePrefix') . $item->facility->price * $item->quantity * $record->reservation->total_nights }}
                            </tr>
                        @endforeach

                        {{-- @foreach ($record->item_details as $key => $item)
                            <tr>
                                <td class="px-6 py-4 text-gray-800">{{ $item['item_name'] }}</td>
                                <td class="px-6 py-4 text-right text-gray-800">{{ $item['quantity'] }}</td>
                                <td class="px-6 py-4 text-right text-gray-800">{{ $item['price'] }}</td>
                                <td class="px-6 py-4 text-right text-gray-900 font-semibold">
                                    {{ trans('frontOffice.room.pricePrefix') . $item['total'] }}</td>
                            </tr>
                        @endforeach --}}

                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="p-8">
                <div class="flex justify-end">
                    <div class="w-full max-w-sm text-gray-800">
                        <div class="flex justify-between items-center py-2 border-b border-gray-300">
                            <span class="font-medium">Subtotal:</span>
                            <span
                                class="font-semibold text-gray-900">{{ trans('frontOffice.room.pricePrefix') . $record->reservation->price }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-300">
                            <span class="font-medium">Tax (11%):</span>
                            <span
                                class="font-semibold text-gray-900">{{ trans('frontOffice.room.pricePrefix') . number_format($record->reservation->price * 0.11, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 mt-4">
                            <span class="text-lg font-semibold">Total:</span>
                            <span
                                class="text-lg font-bold text-primary-500">{{ trans('frontOffice.room.pricePrefix') . ' ' . number_format($record->reservation->price + $record->reservation->price * 0.11, 2) }}</span>
                        </div>
                        <!-- Paid By and Time -->
                        @if ($record->status === 'paid')
                            <div class="mt-4">
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-medium">Paid By:</span>
                                    <span class="text-gray-900">{{ $record->paidBy->name }}</span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="font-medium">Payment Time:</span>
                                    <span class="text-gray-900">
                                        {{ \Carbon\Carbon::parse($record->paid_at)->format('F j, Y, g:i A') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-100 py-4 px-8 text-center text-sm text-gray-600">
                <p>Thank you for your business. For any inquiries, please contact us at <span
                        class="text-gray-800 font-medium">support@suitesense.com</span>.</p>
            </div>
            <!-- No-Print Section -->
            <div class="flex justify-between m-8 no-print">
                <div class="text-center mt-8 no-print">

                    <button onclick="window.print()"
                        class="px-6 py-2 bg-primary-600 hover:bg-primary-500 text-white rounded-lg shadow">
                        <div class="flex justify-center items-center gap-2">
                            @svg('tabler-printer', 'h-6 w-6') Print Invoice
                        </div>

                    </button>
                </div>
            </div>

        </div>


    </div>


</x-layouts.app>
