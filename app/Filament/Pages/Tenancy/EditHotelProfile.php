<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Pages\Tenancy\EditTenantProfile;

class EditHotelProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Hotel profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Hotel Profile Settings')
                    ->description('Please provide the necessary information to edit your hotel profile.')
                    ->icon('tabler-building-community')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Split::make([
                            Forms\Components\Group::make([
                                Forms\Components\TextInput::make('name')
                                    ->label('Hotel Name'),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email'),
                                Forms\Components\TextInput::make('phone')
                                    ->label('Phone'),
                            ])
                                ->columns(1)
                                ->grow('false'),

                        ]),
                        Forms\Components\Group::make([
                            Forms\Components\FileUpload::make('logo_url')
                                ->image()
                                ->imageResizeMode('cover')
                                ->imageEditor()
                                ->columnSpanFull()
                                ->moveFiles()
                                ->label('Hotel Logo') // Custom label for clarity
                                ->helperText('Upload an image logo for the hotel.'), // Helper text for guidance

                        ]),
                        Forms\Components\TextInput::make('country'),
                        Forms\Components\TextInput::make('city'),
                        Forms\Components\Textarea::make('address')
                            ->columnSpanFull(),
                    ]),

                // ...
            ]);
    }
}
