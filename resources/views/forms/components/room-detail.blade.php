@php
    $record = $getRecord();
@endphp

<div class="w-[420px] rounded-lg bg-white p-6 shadow-md border ">
    <div class="flex items-center border-b">
        @svg('tabler-door', 'w-6 h-6 text-primary-500')
        <div class="p-4">
            <h3 class="text-md font-semibold text-gray-900 text-primary-500">{{ $record->room->name }}</h3>
            <p class="text-base font-medium text-gray-800">{{ $record->room->roomType->name }}</p>
        </div>
    </div>

    <div class="mt-6 flex justify-between px-4 text-center ">
        <div>
            <p class="text-xl font-semibold text-gray-900">
                {{ trans('frontOffice.room.priceCurrency') . ' ' . number_format($record->room->price, 2) }}</p>
            <p class="text-sm text-gray-500">Per night</p>
        </div>
        <div>
            <p class="text-xl font-semibold text-gray-900">
                {{ $record->room->status }}</p>
            <p class="text-sm text-gray-500">Status</p>
        </div>
        <div>
            <p class="text-xl font-semibold text-gray-900">{{ $record->total_nights }}</p>
            <p class="text-sm text-gray-500">Total Night</p>
        </div>
    </div>

    <div class="mt-6 space-y-3">
        <div class="flex justify-between">
            <p class="text-sm text-gray-500">Reservation Number</p>
            <x-filament::badge size="sm">
                {{ $record->number }}
            </x-filament::badge>

        </div>

        <div class="flex justify-between">
            <p class="text-sm text-gray-500">Guest Name</p>
            <p class="text-sm font-medium text-gray-900">{{ $record->guest->name ?? '-' }}</p>
        </div>
        <div class="flex justify-between">
            <p class="text-sm text-gray-500">Guest Identity ID</p>
            <p class="text-sm font-medium text-gray-900">{{ $record->guest->identity_number ?? '-' }}</p>
        </div>
        <div class="flex justify-between">
            <p class="text-sm text-gray-500">Guest Phone</p>
            <p class="text-sm font-medium text-gray-900">{{ $record->guest->phone ?? '-' }}</p>
        </div>
    </div>

    <div class="mt-6 border-t pt-3">
        <div class="flex justify-between">
            <p class="text-sm text-gray-500">Total Price</p>
            <p class="text-sm font-medium text-gray-900">
                {{ trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($record->total_price, 2) }}
            </p>
        </div>
    </div>
</div>
