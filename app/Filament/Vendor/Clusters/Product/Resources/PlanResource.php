<?php

namespace App\Filament\Vendor\Clusters\Product\Resources;

use App\Filament\Vendor\Clusters\Product;
use App\Filament\Vendor\Clusters\Product\Resources\PlanResource\Pages;
use App\Filament\Vendor\Clusters\Product\Resources\PlanResource\RelationManagers;
use App\Models\Vendor\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'tabler-file-invoice';

    protected static ?string $cluster = Product::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Plan Details')
                        ->columns([
                            'md' => 1,
                            'xl' => 2,
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Active')
                                ->default(true)
                                ->inline(false)
                                ->required(),
                            Forms\Components\RichEditor::make('description')
                                ->columnSpanFull()
                                ->maxLength(255),

                        ]),

                ]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Plan Pricing')
                        ->columns([
                            'md' => 1,
                            'xl' => 2,
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->numeric(),
                            Forms\Components\TextInput::make('currency')
                                ->maxLength(255),
                            Forms\Components\TextInput::make('duration_in_days')
                                ->label('Duration (days)')
                                ->numeric()
                                ->required(),
                        ])
                ]),
                Forms\Components\Section::make('Plan Features')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Repeater::make('features')
                            ->grid([
                                'md' => 1,
                                'lg' => 2
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\RichEditor::make('description'),
                            ])
                    ]),




            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label(trans('vendor.plan.indexLabel'))
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
                    ->label(trans('vendor.plan.numberLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('vendor.plan.nameLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(trans('vendor.plan.descriptionLabel'))
                    ->html()
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(trans('vendor.plan.priceLabel'))
                    ->money(fn(Model $record): string => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label(trans('vendor.plan.currencyLabel'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(trans('vendor.plan.activeLabel'))
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'view' => Pages\ViewPlan::route('/{record}'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
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
