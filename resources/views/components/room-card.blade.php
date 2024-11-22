<!-- resources/views/components/room-card.blade.php -->
<x-filament::section>
    <x-slot name="heading">
        <span class="text-3xl font-bold text-primary-500 dark:text-white">{{ $room->name }}</span>

    </x-slot>
    <x-slot name="headerEnd">
        <x-filament::button icon="tabler-calendar" tag="a"
            href="{{ route('filament.frontOffice.resources.rooms.manageReservations', ['record' => $room->id, 'tenant' => \Filament\Facades\Filament::getTenant()]) }}">
            Reservation
        </x-filament::button>
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
