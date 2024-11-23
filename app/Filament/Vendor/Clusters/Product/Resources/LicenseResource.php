<?php

namespace App\Filament\Vendor\Clusters\Product\Resources;

use App\Enums\LicenseType;
use App\Filament\Vendor\Clusters\Product;
use App\Filament\Vendor\Clusters\Product\Resources\LicenseResource\Pages;
use App\Filament\Vendor\Clusters\Product\Resources\LicenseResource\RelationManagers;
use App\Models\Vendor\License;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LicenseResource extends Resource
{
    protected static ?string $model = License::class;

    protected static ?string $navigationIcon = 'tabler-file-certificate';

    protected static ?string $cluster = Product::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('License Details')
                        ->columns([
                            'md' => 1,
                            'xl' => 2,
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('key')
                                ->suffixAction(
                                    Forms\Components\Actions\Action::make('copy')
                                        ->label('Copy')
                                        ->color('primary')
                                        ->icon('tabler-clipboard')
                                        ->action(function ($livewire, $state) {
                                            $livewire->dispatch('copy-to-clipboard', text: $state);
                                        })
                                )->extraAttributes([
                                    'x-data' => '{
                                        copyToClipboard(text) {
                                            if (navigator.clipboard && navigator.clipboard.writeText) {
                                                navigator.clipboard.writeText(text).then(() => {
                                                    $tooltip("Copied to clipboard", { timeout: 1500 });
                                                }).catch(() => {
                                                    $tooltip("Failed to copy", { timeout: 1500 });
                                                });
                                            } else {
                                                const textArea = document.createElement("textarea");
                                                textArea.value = text;
                                                textArea.style.position = "fixed";
                                                textArea.style.opacity = "0";
                                                document.body.appendChild(textArea);
                                                textArea.select();
                                                try {
                                                    document.execCommand("copy");
                                                    $tooltip("Copied to clipboard", { timeout: 1500 });
                                                } catch (err) {
                                                    $tooltip("Failed to copy", { timeout: 1500 });
                                                }
                                                document.body.removeChild(textArea);
                                            }
                                        }
                                    }',
                                    'x-on:copy-to-clipboard.window' => 'copyToClipboard($event.detail.text)',
                                ])
                                ->prefixAction(
                                    Forms\Components\Actions\Action::make('generate')
                                        ->label('Generate')
                                        ->color('primary')
                                        ->icon('tabler-key')
                                        ->action(fn(Set $set): string => $set('key', License::generateLicenseKey()))
                                )
                                ->required(),
                            Forms\Components\Select::make('type')
                                ->options(collect(LicenseType::cases())->mapWithKeys(fn($status) => [
                                    $status->value => $status->label(),
                                ])->toArray())
                                ->required()
                                ->default('production'),

                        ]),
                ]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('License Subscription')
                        ->columns([
                            'md' => 1,
                            'xl' => 2,
                        ])
                        ->schema([
                            Forms\Components\Toggle::make('is_active')
                                ->label('Active')
                                ->inline(false)
                                ->required(),
                            Forms\Components\DateTimePicker::make('expires_at')
                                ->required(),
                            Forms\Components\Select::make('subscription_id')
                                ->relationship('subscription', 'id')
                                ->required(),
                            Forms\Components\Select::make('customer_id')
                                ->relationship('customer', 'id')
                                ->required(),
                        ])
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Key'),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subscription.plan.name'),
                Tables\Columns\TextColumn::make('customer.full_name'),
                Tables\Columns\TextColumn::make('number')
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
            'index' => Pages\ListLicenses::route('/'),
            'create' => Pages\CreateLicense::route('/create'),
            'view' => Pages\ViewLicense::route('/{record}'),
            'edit' => Pages\EditLicense::route('/{record}/edit'),
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
