<div class="w-full max-w-md bg-white rounded-lg border border-primary-500 overflow-hidden">
    {{-- <img src="https://via.placeholder.com/400x200" alt="Room Image" class="w-full h-48 object-cover" /> --}}
    <div class="p-6">
        {{-- <!-- Room Type and Name Section -->
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-gray-500">Room Type: <span class="text-gray-800">Deluxe</span></p>
            <p class="text-sm font-medium text-gray-500">Room Name: <span class="text-gray-800">Ocean View
                    Suite</span></p>
        </div> --}}

        <h3 class="text-xl font-bold text-primary-600 mt-4">
            @php
                if (str_contains($room->name, 'Room') || str_contains($room->name, 'room')) {
                    $name = $room->name;
                } else {
                    $name = 'Room ' . $room->name;
                }
            @endphp
            {{ $name }}</h3>
        <p class="text-xs text-gray-500 mt-2">{{ $room->roomType->description ?? '-' }}</p>

        <!-- Price and Availability -->
        <div class="flex items-center justify-between mt-4">
            <div>
                <p class="text-lg font-bold text-primary-500">${{ number_format($room->price, 2) }}</p>
                <p class="text-sm text-gray-500">Per Night</p>
            </div>
            @if ($room->status === 'available')
                <p class="text-sm font-medium text-white bg-success hover:bg-success px-3 py-1 rounded-lg transition">
                    {{ ucfirst($room->status) }}
                </p>
            @elseif ($room->status === 'occupied')
                <p class="text-sm font-medium text-white bg-warning hover:bg-warning px-3 py-1 rounded-lg transition">
                    {{ ucfirst($room->status) }}
                </p>
            @elseif ($room->status === 'booked')
                <p class="text-sm font-medium text-white bg-danger hover:bg-secondary px-3 py-1 rounded-lg transition">
                    {{ ucfirst($room->status) }}
                </p>
            @endif
        </div>

        <!-- Features Section -->
        <div class="mt-6">
            <h4 class="text-lg font-semibold text-primary-500 mb-3">Facilities</h4>
            <ul>
                @if ($room->roomType->facilities && is_array($room->roomType->facilities))
                    @foreach ($room->roomType->facilities as $facility)
                        <li class="flex items-center text-xs">
                            <svg class="w-5 h-5 text-primary-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $facility }}
                        </li>
                    @endforeach
                @elseif($room->roomType->facilities && is_string($room->roomType->facilities))
                    @foreach (json_decode($room->roomType->facilities) as $facility)
                        <li class="flex items-center text-xs">
                            <svg class="w-5 h-5 text-primary-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $facility }}
                        </li>
                    @endforeach
                @else
                    <p class="text-sm text-gray-600">No facilities listed.</p>
                @endif
            </ul>
        </div>

        <button wire:navigate
            href="{{ route('filament.frontOffice.resources.rooms.manageReservations', ['record' => $room->id, 'tenant' => \Filament\Facades\Filament::getTenant()]) }}"
            class="w-full mt-6 bg-primary hover:bg-primary text-white py-3 rounded-lg transition font-medium">
            View Reservations
        </button>



    </div>
</div>
