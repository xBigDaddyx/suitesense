<x-filament-widgets::widget>
    <x-filament::section icon="tabler-door">
        <x-slot name="heading">
            Available Rooms
        </x-slot>

        @foreach ($this->getGroupedRooms() as $roomType => $rooms)
            <div class="mb-8">
                <div class="mt-2 mb-4 bg-primary-50 text-sm rounded-lg p-4 dark:bg-white dark:text-neutral-800"
                    role="alert" tabindex="-1" aria-labelledby="hs-solid-color-dark-label">
                    <div class="flex-1 md:flex md:justify-between ms-2">
                        <p><span id="hs-solid-color-dark-label" class="font-bold">{{ $roomType }}</p>
                        <p class="text-sm mt-3 md:mt-0 md:ms-6">
                            <x-filament::badge icon="tabler-door" color="primary">
                                {{ $rooms->count() }}
                                {{ Str::plural('room', $rooms->count()) }}
                            </x-filament::badge>

                        </p>
                    </div>

                </div>
                @if ($rooms->isEmpty())
                    <p class="text-gray-500">No rooms available in this category.</p>
                @else
                    <!-- Tailwind Grid Layout for Each Room Type -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($rooms as $room)
                            <div class="col-span-1">
                                <x-room-card :room="$room" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach


    </x-filament::section>


</x-filament-widgets::widget>