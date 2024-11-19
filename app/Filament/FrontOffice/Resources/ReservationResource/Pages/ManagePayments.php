<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Pages;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Filament\FrontOffice\Resources\PaymentResource;
use App\Filament\FrontOffice\Resources\ReservationResource;
use App\Models\Payment;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManagePayments extends ManageRelatedRecords
{
    protected static string $resource = ReservationResource::class;

    protected static string $relationship = 'payments';

    protected static ?string $navigationIcon = 'tabler-credit-card';

    public static function getNavigationLabel(): string
    {
        return 'Manage Payments';
    }

    public function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\TextInput::make('amount')
                ->live(onBlur: true)
                ->readOnly(fn(Get $get): bool => $get('status') === 'completed')
                ->maxValue(fn(Get $get): ?int => $get('../../total_price'))
                ->required()
                ->numeric()
                ->prefix(trans('frontOffice.reservation.priceCurrency')),

            Forms\Components\Select::make('status')
                ->hiddenOn('create')
                ->disableOptionWhen(fn($state): bool => $state === 'completed')
                ->live()
                ->options(collect(PaymentStatus::cases())->mapWithKeys(fn($status) => [
                    $status->value => $status->label(),
                ])->toArray())
                ->required(),
            Forms\Components\Select::make('method')
                ->disableOptionWhen(fn(Get $get): bool => $get('status') === 'completed')
                ->options(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                    $status->value => $status->label(),
                ])->toArray())
                ->required(),
            Forms\Components\Select::make('type')
                ->options(collect(PaymentType::cases())->mapWithKeys(fn($status) => [
                    $status->value => $status->label(),
                ])->toArray())
                ->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return PaymentResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('tabler-plus')
            ]);
        // return $table
        //     ->recordTitleAttribute('amount')
        //     ->columns([
        //         Tables\Columns\TextColumn::make('amount'),
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
        //     ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
        //         SoftDeletingScope::class,
        //     ]));
    }
}
