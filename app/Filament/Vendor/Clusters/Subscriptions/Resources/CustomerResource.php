<?php

namespace App\Filament\Vendor\Clusters\Subscriptions\Resources;

use App\Filament\Vendor\Clusters\Subscriptions;
use App\Filament\Vendor\Clusters\Subscriptions\Resources\CustomerResource\Pages;
use App\Filament\Vendor\Clusters\Subscriptions\Resources\CustomerResource\RelationManagers;
use App\Models\Vendor\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'tabler-user-star';

    protected static ?string $cluster = Subscriptions::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Customer Details')
                        ->columns([
                            'md' => 1,
                            'xl' => 2,
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('first_name')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('last_name')
                                ->maxLength(255),

                        ]),

                ]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Billing Details')
                        ->columns([
                            'md' => 1,
                            'xl' => 2,
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('phone_number')
                                ->tel()
                                ->maxLength(255),

                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('mobile_number')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('address')
                                ->columnSpanFull(),
                        ])
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label(trans('vendor.customer.indexLabel'))
                    ->state(
                        static function (HasTable $livewire, stdClass $rowLoop): string {
                            return (string) (
                                $rowLoop->iteration +
                                ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                            );
                        }
                    ),
                Tables\Columns\TextColumn::make('number')
                    ->label(trans('vendor.customer.numberLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->label(trans('vendor.customer.firstNameLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label(trans('vendor.customer.lastNameLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(trans('vendor.customer.emailLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->label(trans('vendor.customer.phoneLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile_number')
                    ->label(trans('vendor.customer.mobileNumberLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label(trans('vendor.customer.addressLabel'))
                    ->limit(50)
                    ->searchable(),


            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
