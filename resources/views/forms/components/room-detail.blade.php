@php
    $record = $getRecord();
@endphp
<div class="mx-auto max-w-fit overflow-hidden rounded-xl bg-white shadow-xl">
    <!-- Header with Gradient Background -->
    <div class="w-full bg-primary-500 p-8 text-center">
        <h3 class="text-3xl font-extrabold text-white">Reservation Details</h3>
        <p class="mt-2 text-sm text-white">Reservation # <span
                class="font-semibold text-white">{{ $record->number }}</span></p>
    </div>

    <!-- Card Body -->
    <div class="space-y-6 p-6">

        <!-- Guest Info Section -->
        <div class="space-y-4">
            <h4 class="text-xl font-semibold text-gray-800">Guest Information</h4>
            <div class="flex items-center space-x-4">

                <div>
                    <p class="text-lg font-semibold text-gray-800">{{ $record->guest->name }}</p>
                    <p class="text-sm text-gray-600">Identity Number: {{ $record->guest->identity_number }}</p>
                    <p class="text-sm text-gray-600">Phone: {{ $record->guest->phone }}</p>
                    <p class="text-sm text-gray-600">Email: {{ $record->guest->email }}</p>
                </div>
            </div>
        </div>

        <!-- Room and Date Info Section -->
        <div class="space-y-4">
            <h4 class="text-xl font-semibold text-gray-800">{{ $record->room->roomType->name }}</h4>
            <div class="space-y-2">
                <p class="text-base text-gray-600">Room: <span class="font-semibold">{{ $record->room->name }}</span>
                </p>
                <p class="text-base text-gray-600">Check-In: <span
                        class="font-semibold">{{ \Carbon\Carbon::parse($record->check_in)->format('M j, Y') }}</span>
                </p>
                <p class="text-base text-gray-600">Checkout Date: <span
                        class="font-semibold">{{ \Carbon\Carbon::parse($record->check_out)->format('M j, Y') }}</span>
                </p>
                <p class="text-base text-gray-600">Duration: <span class="font-semibold">{{ $record->total_nights }}
                        Nights</span></p>
            </div>
        </div>
    </div>
</div>
