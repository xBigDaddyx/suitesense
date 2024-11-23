<?php

namespace App\Filament\Vendor\Resources;

use App\Enums\NotificationChannel;
use App\Filament\Vendor\Resources\OrderResource\Pages;
use App\Filament\Vendor\Resources\OrderResource\RelationManagers;
use App\Models\Vendor\Order;
use App\Models\Vendor\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('external_id')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('payer_email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('invoice_duration')
                    ->maxLength(255),
                Forms\Components\TextInput::make('callback_virtual_account_id')
                    ->maxLength(255),
                Forms\Components\Toggle::make('should_send_email')
                    ->required(),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'id')
                    ->required(),

                Forms\Components\TextInput::make('success_redirect_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('failure_redirect_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('payment_methods'),
                Forms\Components\TextInput::make('mid_label')
                    ->maxLength(255),
                Forms\Components\Toggle::make('should_authenticate_credit_card')
                    ->required(),
                Forms\Components\TextInput::make('currency')
                    ->maxLength(255),
                Forms\Components\TextInput::make('reminder_time')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('local')
                    ->maxLength(255),
                Forms\Components\TextInput::make('reminder_time_unit')
                    ->maxLength(255),
                Forms\Components\Repeater::make('items')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->options(function () {
                                return Product::all()->pluck('name', 'id');
                            })
                            ->required(),
                        Forms\Components\TextInput::make('name'),
                        Forms\Components\TextInput::make('url'),
                        Forms\Components\TextInput::make('category'),
                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->inputMode('decimal')
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->inputMode('decimal')
                            ->required(),
                    ]),
                Forms\Components\Repeater::make('fees')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('type'),
                        Forms\Components\TextInput::make('value')
                            ->numeric()
                            ->inputMode('decimal'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),
                Tables\Columns\TextColumn::make('external_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payer_email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('invoice_duration')
                    ->searchable(),
                Tables\Columns\TextColumn::make('callback_virtual_account_id')
                    ->searchable(),
                Tables\Columns\IconColumn::make('shoudl_send_email')
                    ->boolean(),
                Tables\Columns\TextColumn::make('customer.id'),
                Tables\Columns\TextColumn::make('customerNotificationPreference.id'),
                Tables\Columns\TextColumn::make('success_redirect_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('failure_redirect_url')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mid_label')
                    ->searchable(),
                Tables\Columns\IconColumn::make('should_authenticate_credit_card')
                    ->boolean(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reminder_time')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('local')
                    ->searchable(),
                Tables\Columns\TextColumn::make('reminder_time_unit')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_series')
                    ->searchable(),
                Tables\Columns\TextColumn::make('number_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
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
