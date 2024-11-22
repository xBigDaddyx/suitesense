<x-filament-widgets::widget>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach ($this->getGroupedRooms() as $roomType => $rooms)
            <div class="mb-8">
                <div class="mt-2 mb-4 bg-primary-300 text-sm rounded-lg p-4 dark:bg-primary-600 dark:text-neutral-800"
                    role="alert" tabindex="-1" aria-labelledby="hs-solid-color-dark-label">
                    <div class="flex-1 md:flex md:justify-between ms-2">
                        <p><span id="hs-solid-color-dark-label"
                                class="font-bold text-primary-800 dark:text-white">{{ $roomType }}</p>
                        <p class="text-sm mt-3 md:mt-0 md:ms-6 dark:text-white">
                            <x-filament::badge icon="tabler-door" color="primary" class="dark:text-white">
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
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
                        @foreach ($rooms as $room)
                            <div class="col-span-1">
                                <x-room-card :room="$room" />
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

    </div>





</x-filament-widgets::widget>
