<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources;

use App\Filament\Vendor\Clusters\Clients;
use App\Filament\Vendor\Clusters\Clients\Resources\OrderResource\Pages;
use App\Filament\Vendor\Clusters\Clients\Resources\OrderResource\RelationManagers;
use App\Models\Vendor\Order;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Clients::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Details')
                    ->description('Please provide the necessary information to add a new order. Make sure to fill in all required fields.')
                    ->icon('tabler-file-text')
                    ->columns([
                        'md' => 1,
                        'xl' => 2,
                    ])
                    ->schema([
                        TableRepeater::make('transaction_details')

                            ->headers([
                                'md' => 1,
                                'xl' => 2,
                            ])
                            ->headers([
                                Header::make('order_id')->align('center'),
                                Header::make('gross_amount')->align('center'),
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('order_id')
                                    ->required(),
                                Forms\Components\TextInput::make('gross_amount')
                                    ->required(),
                            ]),
                        TableRepeater::make('customer_details')

                            ->headers([
                                'md' => 1,
                                'xl' => 2,
                            ])
                            ->headers([
                                Header::make('first_name')->align('center'),
                                Header::make('email')->align('center'),
                            ])
                            ->schema([
                                Forms\Components\TextInput::make('first_name')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\TextInput::make('customer_details')
                    ->required(),
                Forms\Components\TextInput::make('item_details')
                    ->required(),
                Forms\Components\TextInput::make('payment_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending'),
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_series')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_order')
                    ->numeric(),
                Forms\Components\TextInput::make('deleted_by')
                    ->numeric(),
                Forms\Components\TextInput::make('created_by')
                    ->numeric(),
                Forms\Components\TextInput::make('updated_by')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
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
                    ->label('Order Number'),
                Tables\Columns\TextColumn::make('transaction_details.order_id')
                    ->label('Transaction ID'),
                Tables\Columns\TextColumn::make('customer_details.first_name')
                    ->label('Customer Name'),
                Tables\Columns\TextColumn::make('customer_details.email')
                    ->label('Customer Email'),
                Tables\Columns\TextColumn::make('transaction_details.gross_amount')
                    ->money()
                    ->label('Gross Amount'),
                Tables\Columns\TextColumn::make('payment_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
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
