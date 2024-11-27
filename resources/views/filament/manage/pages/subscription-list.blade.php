<x-filament::page>
    <x-filament::grid default="1" md="3">
        @foreach (\App\Models\Vendor\Plan::all() as $record)
            <x-pricing-card>
                <x-slot:plan-name>
                    {{ $record->name }}
                </x-slot>
                <x-slot:plan-description>
                    {{ $record->description }}
                </x-slot>
                <x-slot:plan-price>
                    {{ $record->currency . ' ' . number_format($record->price, 0) }}
                </x-slot>
                <x-slot:plan-features>
                    @foreach ($record->features as $feature)
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-primary-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <span> {{ $feature['name'] }}</span>
                        </li>
                    @endforeach
                </x-slot:plan-features>
                <x-slot:plan-actions>

                    <x-filament-actions::group :actions="[
                        ($this->editAction)(['plan' => $record->id]),
                        ($this->deleteAction)(['plan' => $record->id]),
                    ]" />



                </x-slot:plan-actions>
                <x-slot:subscribe-action>
                    {{ ($this->subscribeAction)(['plan' => $record->id]) }}
                </x-slot:subscribe-action>
            </x-pricing-card>
        @endforeach
        <x-filament-actions::modals />
    </x-filament::grid>
</x-filament::page>
