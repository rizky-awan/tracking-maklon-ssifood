<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Filament\Resources\StockMovementResource\RelationManagers;
use App\Models\StockMovement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Stock Management';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->role === 'admin'
            || auth()->user()->role === 'sales'
            || auth()->user()->role === 'r&d'
            || auth()->user()->role === 'purchasing'
            || auth()->user()->role === 'production';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin'
            || auth()->user()->role === 'sales'
            || auth()->user()->role === 'r&d'
            || auth()->user()->role === 'purchasing'
            || auth()->user()->role === 'production';
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role === 'admin'
            || auth()->user()->role === 'purchasing'
            || auth()->user()->role === 'production';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->label('Item')
                    ->options(
                        \App\Models\Item::all()->mapWithKeys(function ($item) {
                            return [
                                $item->id => ($item->code ? $item->code . ' - ' : '') . $item->name
                            ];
                        })
                    )
                    ->searchable()
                    ->required()
                    ->reactive(),

                Forms\Components\Select::make('type')
                    ->options([
                        'IN' => 'Stock In',
                        'OUT' => 'Stock Out',
                    ])
                    ->required()
                    ->reactive(),

                Forms\Components\TextInput::make('qty')
                    ->numeric()
                    ->required(),

                Forms\Components\DatePicker::make('date')
                    ->default(now())
                    ->required(),

               TextInput::make('batch_number')
                    ->label('Batch Number')
                    ->visible(fn ($get) => $get('type') === 'IN' 
                        && optional(\App\Models\Item::find($get('item_id')))->track_expiry)
                    ->required(fn ($get) => $get('type') === 'IN' 
                        && optional(\App\Models\Item::find($get('item_id')))->track_expiry),

                DatePicker::make('expired_at')
                    ->label('Expired Date')
                    ->visible(fn ($get) => $get('type') === 'IN' 
                        && optional(\App\Models\Item::find($get('item_id')))->track_expiry)
                    ->required(fn ($get) => $get('type') === 'IN' 
                        && optional(\App\Models\Item::find($get('item_id')))->track_expiry),

                Forms\Components\Select::make('source')
                    ->options([
                        'purchase' => 'Purchase',
                        'production' => 'Production',
                        'adjustment' => 'Adjustment',
                        'consignment' => 'Consignment',
                    ]),

                Forms\Components\Textarea::make('notes'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('item.name') 
                    ->label('Item')
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'success' => 'IN',
                        'danger' => 'OUT',
                    ])
                    ->aligncenter(),

                Tables\Columns\TextColumn::make('qty') ->alignCenter(),

                Tables\Columns\TextColumn::make('date') ->date() ->alignCenter(),

                Tables\Columns\TextColumn::make('source') ->alignCenter(),

                Tables\Columns\TextColumn::make('batch_number')
                    ->label('Batch'),

                Tables\Columns\TextColumn::make('expired_at')
                    ->label('Expiry Date')
                    ->date(), 

                Tables\Columns\TextColumn::make('notes') ->limit(50),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'IN' => 'Stock In',
                        'OUT' => 'Stock Out',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
            'edit' => Pages\EditStockMovement::route('/{record}/edit'),
        ];
    }
}
