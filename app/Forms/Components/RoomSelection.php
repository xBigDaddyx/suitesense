<?php

namespace App\Forms\Components;

use App\Concerns\HasColors;
use App\Concerns\HasDescription;
use App\Concerns\HasIcons;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasAlignment;
use Filament\Support\Concerns\HasColor;
use Filament\Support\Concerns\HasIcon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Htmlable;
use Closure;
use Filament\Forms\Components\Concerns\HasOptions;

class RoomSelection extends Field
{
    use HasAlignment;
    use HasColor;
    use HasIcon;
    use HasOptions;
    protected array|Arrayable|Closure|string|null $icons = null;
    protected array|Arrayable|Closure|string $descriptions = [];
    protected array|Arrayable|Closure|string $colors = [];

    protected string $view = 'forms.components.room-selection';

    public function icons(array|Arrayable|string|Closure|null $icons): static
    {
        $this->icons = $icons;

        return $this;
    }
    public function colors(array|Arrayable|string|Closure|null $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    public function hasIcons($value): bool
    {
        if ($value !== null && ! empty($this->getIcons())) {
            return array_key_exists($value, $this->getIcons());
        }

        return false;
    }
    public function hasColors($value): bool
    {
        if ($value !== null && ! empty($this->getcolors())) {
            return array_key_exists($value, $this->getcolors());
        }

        return false;
    }
    /**
     * @return array | Closure | null
     */
    public function getIcons(): mixed
    {
        $icons = $this->evaluate($this->icons);

        $enum = $icons;

        if (is_string($enum) && enum_exists($enum)) {
            if (is_a($enum, HasIcons::class, allow_string: true)) {
                return collect($enum::cases())
                    ->mapWithKeys(fn($case) => [
                        ($case?->value ?? $case->name) => $case->getIcons() ?? $case->name,
                    ])
                    ->all();
            }

            return collect($enum::cases())
                ->mapWithKeys(fn($case) => [
                    ($case?->value ?? $case->name) => $case->name,
                ])
                ->all();
        }

        if ($icons instanceof Arrayable) {
            $icons = $icons->toArray();
        }

        return $icons;
    }

    public function getIcon($value): ?string
    {
        return $this->getIcons()[$value] ?? null;
    }

    /**
     * @param  array<string | Htmlable> | Arrayable | string | Closure  $descriptions
     */
    public function descriptions(array|Arrayable|string|Closure $descriptions): static
    {
        $this->descriptions = $descriptions;

        return $this;
    }

    /**
     * @param  array-key  $value
     */
    public function hasDescription($value): bool
    {
        return array_key_exists($value, $this->getDescriptions());
    }

    /**
     * @param  array-key  $value
     */
    public function getDescription($value): string|Htmlable|null
    {
        return $this->getDescriptions()[$value] ?? null;
    }

    /**
     * @return array<string | Htmlable>
     */
    public function getDescriptions(): array
    {
        $descriptions = $this->evaluate($this->descriptions);

        $enum = $descriptions;

        if (is_string($enum) && enum_exists($enum)) {
            if (is_a($enum, HasDescription::class, allow_string: true)) {
                return collect($enum::cases())
                    ->mapWithKeys(fn($case) => [
                        ($case?->value ?? $case->name) => $case->getDescriptions() ?? $case->name,
                    ])
                    ->all();
            }

            return collect($enum::cases())
                ->mapWithKeys(fn($case) => [
                    ($case?->value ?? $case->name) => $case->name,
                ])
                ->all();
        }

        if ($descriptions instanceof Arrayable) {
            $descriptions = $descriptions->toArray();
        }

        return $descriptions;
    }

    public function getDefaultState(): mixed
    {
        $state = parent::getDefaultState();

        if (is_bool($state)) {
            return $state ? 1 : 0;
        }

        return $state;
    }
    /**
     * @return array<string | Htmlable>
     */
    public function getColors(): array
    {
        $descriptions = $this->evaluate($this->descriptions);

        $enum = $descriptions;

        if (is_string($enum) && enum_exists($enum)) {
            if (is_a($enum, HasColors::class, allow_string: true)) {
                return collect($enum::cases())
                    ->mapWithKeys(fn($case) => [
                        ($case?->value ?? $case->name) => $case->getColors() ?? $case->name,
                    ])
                    ->all();
            }

            return collect($enum::cases())
                ->mapWithKeys(fn($case) => [
                    ($case?->value ?? $case->name) => $case->name,
                ])
                ->all();
        }

        if ($descriptions instanceof Arrayable) {
            $descriptions = $descriptions->toArray();
        }

        return $descriptions;
    }
}
