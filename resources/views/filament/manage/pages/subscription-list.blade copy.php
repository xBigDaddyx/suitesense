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
                        <li>
                            <span class="card-pricing-feature-checklist">
                                <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </span>
                            <span class="card-pricing-feature-title">
                                {{ $feature['name'] }}
                            </span>
                        </li>
                    @endforeach
                </x-slot:plan-features>
                <x-slot:plan-actions>


                    {{ ($this->subscribeAction)(['plan' => $record->id]) }}

                    {{ ($this->editAction)(['plan' => $record->id]) }}
                    {{ ($this->deleteAction)(['plan' => $record->id]) }}


                </x-slot:plan-actions>
            </x-pricing-card>
        @endforeach
        <x-filament-actions::modals />
    </x-filament::grid>
</x-filament::page>
