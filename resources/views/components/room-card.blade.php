<!-- resources/views/components/room-card.blade.php -->
<x-filament::section icon="tabler-door" icon-color="primary">
    <x-slot name="heading">
        {{ $room->name }}
    </x-slot>
    <x-slot name="headerEnd">
        <x-filament::button icon="tabler-arrow-right">
            Book Now
        </x-filament::button>
        <x-filament::badge color="primary" href="{{ ManageReservation }}">
            {{ $room->roomType->name }}
        </x-filament::badge>


    </x-slot>
    <strong>Price:</strong> ${{ $room->price }} <br>
    <strong>Status:</strong> {{ $room->is_available ? 'Available' : 'Not Available' }}
    @if ($room->roomType->facilities && is_array($room->roomType->facilities))
        <div class="mt-2">
            <strong>Facilities:</strong>
            <ul class="list-disc pl-5 text-sm text-gray-600">
                @foreach ($room->roomType->facilities as $facility)
                    <li>{{ $facility }}</li>
                @endforeach
            </ul>
        </div>
    @elseif($room->roomType->facilities && is_string($room->roomType->facilities))
        <div class="mt-2">
            <strong>Facilities:</strong>
            <ul class="space-y-3 text-sm">
                @foreach (json_decode($room->roomType->facilities) as $facility)
                    <li class="flex gap-x-3">
                        <span class=" w-4 h-4">@svg('tabler-check')</span>
                        <span class="text-primary-500 dark:text-white">
                            {{ $facility }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <p class="text-sm text-gray-600">No facilities listed.</p>
    @endif
</x-filament::section>