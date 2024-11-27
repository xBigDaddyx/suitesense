<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources;

use App\Filament\Vendor\Clusters\Clients;
use App\Filament\Vendor\Clusters\Clients\Resources\SubscriptionResource\Pages;
use App\Filament\Vendor\Clusters\Clients\Resources\SubscriptionResource\RelationManagers;
use App\Models\Vendor\Plan;
use App\Models\Vendor\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'tabler-cash';

    protected static ?string $cluster = Clients::class;
    public static function isScopedToTenant(bool $condition = true): bool
    {
        if (auth()->user()->hasRole('Vendor')) {
            return false;
        }
        return true;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('hotel_id')
                    ->relationship('hotel', 'id')
                    ->loadingMessage('Loading hotel...')
                    ->searchable(['first_name', 'last_name', 'email', 'address', 'phone_number', 'mobile_number'])
                    ->searchPrompt('Search hotel by their name, email, address or phone.')
                    ->allowHtml()
                    ->native(false)
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "<span class='text-primary-500 font-bold'>{$record->name}</span><br>Phone : {$record->phone}<br>Address : {$record->address}")
                    ->required(),
                Forms\Components\Select::make('plan_id')
                    ->relationship('plan', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('starts_at')
                    ->native(false)
                    ->default(now()),

                Forms\Components\Toggle::make('is_active')
                    ->inline(false)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Number'),
                Tables\Columns\TextColumn::make('hotel.name'),
                Tables\Columns\TextColumn::make('plan.name'),
                Tables\Columns\TextColumn::make('starts_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'view' => Pages\ViewSubscription::route('/{record}'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
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
