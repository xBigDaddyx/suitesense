<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources\CustomerResource\Pages;

use App\Filament\Vendor\Clusters\Clients\Resources\CustomerResource;
use App\Filament\Vendor\Clusters\Clients\Resources\SubscriptionResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageSubscription extends ManageRelatedRecords
{
    protected static string $resource = CustomerResource::class;

    protected static string $relationship = 'subscriptions';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Subscriptions';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return SubscriptionResource::table($table);
        // return $table
        //     ->recordTitleAttribute('number')
        //     ->columns([
        //         Tables\Columns\TextColumn::make('number'),
        //     ])
        //     ->filters([
        //         Tables\Filters\TrashedFilter::make()
        //     ])
        //     ->headerActions([
        //         Tables\Actions\CreateAction::make(),
        //         Tables\Actions\AssociateAction::make(),
        //     ])
        //     ->actions([
        //         Tables\Actions\ViewAction::make(),
        //         Tables\Actions\EditAction::make(),
        //         Tables\Actions\DissociateAction::make(),
        //         Tables\Actions\DeleteAction::make(),
        //         Tables\Actions\ForceDeleteAction::make(),
        //         Tables\Actions\RestoreAction::make(),
        //     ])
        //     ->bulkActions([
        //         Tables\Actions\BulkActionGroup::make([
        //             Tables\Actions\DissociateBulkAction::make(),
        //             Tables\Actions\DeleteBulkAction::make(),
        //             Tables\Actions\RestoreBulkAction::make(),
        //             Tables\Actions\ForceDeleteBulkAction::make(),
        //         ]),
        //     ])
        //     ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
        //         SoftDeletingScope::class,
        //     ]));
    }
}
