<x-filament-widgets::widget>
    <div class="grid grid-flow-col auto-cols-auto gap-4">
        @foreach ($this->getRecords() as $key => $stat)
            <div @class([
                'w-full bg-red-50 flex flex-col bg-white border border-t-4 border-t-red-300 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-red-400 dark:shadow-neutral-700/70' =>
                    $key == 'cancelled',
                'w-full flex bg-green-50 flex-col bg-white border border-t-4 border-t-green-300 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-green-400 dark:shadow-neutral-700/70' =>
                    $key == 'completed',
                'w-full flex bg-blue-50 flex-col bg-white border border-t-4 border-t-blue-300 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-400 dark:shadow-neutral-700/70pw-full flex flex-col bg-white border border-t-4 border-t-blue-600 shadow-sm rounded-xl dark:bg-neutral-900 dark:border-neutral-700 dark:border-t-blue-500 dark:shadow-neutral-700/70' =>
                    $key == 'confirmed',
            ])>

                <div class="p-4 md:p-5">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">
                        {{ ucfirst($key) }} Reservations
                    </h3>

                    <p class="mt-2 text-gray-500 dark:text-neutral-400 text-2xl font-bold">
                        {{ $stat }}
                    </p>
                    <p>
                        {{  }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>


</x-filament-widgets::widget>
