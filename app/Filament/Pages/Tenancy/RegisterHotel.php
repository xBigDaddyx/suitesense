<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use App\Models\User;
use App\Models\Vendor\Hotel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Facades\Http;
use Teguh02\IndonesiaTerritoryForms\IndonesiaTerritoryForms;

class RegisterHotel extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Your Hotel';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns([
                        'sm' => 3,
                        'xl' => 4,
                        '2xl' => 5,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->prefixIcon('tabler-id-badge-2')
                            ->required()
                            ->regex('/^[a-zA-Z ]*$/')
                            ->validationMessages([
                                'regex' => 'The :attribute must contain only letters.',
                            ])
                            ->autocapitalize('words')
                            ->autofocus()
                            ->autocomplete(false)
                            ->type('text')
                            ->placeholder('eg. Hotel Swiss')
                            ->minLength(2)
                            ->maxLength(255)
                            ->label('Hotel Name'),
                        Forms\Components\TextInput::make('email')
                            ->prefixIcon('tabler-mail')
                            ->placeholder('eg. hotel@swiss.com')
                            ->required()
                            ->regex('/^.+@.+$/i')
                            ->validationMessages([
                                'regex' => 'The :attribute must be a valid email address.',
                            ])
                            ->autocomplete(false)
                            ->label('Hotel Email'),
                        Forms\Components\TextInput::make('phone')
                            ->prefixIcon('tabler-phone')
                            ->placeholder('eg. +44 123456789')
                            ->required()
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                            ->validationMessages([
                                'regex' => 'The :attribute must be a valid phone number.',
                            ])
                            ->autocomplete(false)
                            ->label('Hotel Phone'),
                        Forms\Components\Fieldset::make('Location Information')
                            ->columns([
                                'sm' => 3,
                                'xl' => 4,
                                '2xl' => 5,
                            ])
                            ->schema([
                                Forms\Components\Select::make('province')
                                    ->prefixIcon('tabler-map')
                                    ->live()
                                    ->options(function () {
                                        $data = Http::get('https://wilayah.id/api/provinces.json');
                                        $result = $data->json();
                                        return collect($result['data'])->pluck('name', 'code');
                                    }),
                                Forms\Components\Select::make('city')
                                    ->prefixIcon('tabler-map')
                                    ->live()
                                    ->options(function (Get $get) {
                                        if ($get('province') != null) {
                                            $data = Http::get('https://wilayah.id/api/regencies/' . $get('province') . '.json');
                                            $result = $data->json();
                                            return collect($result['data'])->pluck('name', 'code');
                                        }
                                    }),
                                Forms\Components\Select::make('district')
                                    ->prefixIcon('tabler-map')
                                    ->live()
                                    ->options(function (Get $get) {
                                        if ($get('city') != null) {
                                            $data = Http::get('https://wilayah.id/api/districts/' . $get('city') . '.json');
                                            $result = $data->json();
                                            return collect($result['data'])->pluck('name', 'code');
                                        }
                                    }),
                                Forms\Components\Select::make('subdistrict')
                                    ->prefixIcon('tabler-map')
                                    ->live()
                                    ->options(function (Get $get) {
                                        if ($get('district') != null) {
                                            $data = Http::get('https://wilayah.id/api/villages/' . $get('district') . '.json');
                                            $result = $data->json();
                                            return collect($result['data'])->pluck('name', 'code');
                                        }
                                    })
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        if ($get('subdistrict') != null) {
                                            $data = Http::get('https://wilayah.id/api/villages/' . $get('district') . '.json');
                                            $response = $data->json();

                                            $result = \Illuminate\Support\Arr::first($response['data'], function ($value, $key) use ($get) {

                                                return $value['code'] === $get('subdistrict');
                                            });
                                            $set('postal_code', collect($result)['postal_code']);
                                        }
                                    }),
                                Forms\Components\TextInput::make('postal_code')
                                    ->prefixIcon('tabler-map-code')
                                    ->live(),
                                Forms\Components\Textarea::make('address')
                                    ->columnSpanFull(),
                            ]),

                    ]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('logo_url')
                            ->required()
                            ->image()
                            ->imageResizeMode('cover')
                            ->imageEditor()
                            ->columnSpan(2)
                            ->moveFiles()
                            ->label('Hotel Logo') // Custom label for clarity
                            ->helperText('Upload an image logo for the hotel.'), // Helper text for guidance
                    ])

            ]);
    }

    protected function handleRegistration(array $data): Hotel
    {
        $team = Hotel::create($data);

        $team->members()->attach(auth()->user(), ['department' => 'Mangement', 'job_title' => 'Owner']);
        $user = User::find(auth()->user()->id);
        $user->hotel_id = $team->id;
        $user->save();

        return $team;
    }
}
