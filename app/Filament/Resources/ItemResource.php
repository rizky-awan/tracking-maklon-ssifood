<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Filament\Resources\ItemResource\RelationManagers;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Filters\SelectFilter;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
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
        return auth()->user()->role === 'admin'
            || auth()->user()->role === 'purchasing'
            || auth()->user()->role === 'production';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Item Code')
                    ->visible(fn ($get) => $get('type') === 'raw_material') // 🔥 hanya raw material
                    ->maxLength(50)
                    ->required(fn ($get) => $get('type') === 'raw_material'),

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\Select::make('type')
                    ->options([
                        'raw_material' => 'Raw Material',
                        'packaging' => 'Packaging',
                    ])
                    ->required()
                    ->reactive(), // 🔥 penting

                Forms\Components\TextInput::make('unit')
                    ->placeholder('kg / pcs / liter, etc.')
                    ->required(),

                Forms\Components\TextInput::make('min_stock')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('track_expiry')
                    ->label('Track Expiry')
                    ->visible(fn ($get) => $get('type') === 'raw_material') // 🔥 hanya raw material
                    ->default(false),

                Forms\Components\TextInput::make('producer')
                    ->label('Producer')
                    ->nullable()
                    ->placeholder('Optional')
                    ->visible(fn ($get) => $get('type') === 'raw_material') // 🔥 hanya raw material
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->placeholder('-')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'success' => 'raw_material',
                        'gray' => 'packaging',
                    ]),

                Tables\Columns\TextColumn::make('unit'),

                Tables\Columns\TextColumn::make('min_stock'),
                
                Tables\Columns\TextColumn::make('producer')
                    ->colors([
                        'success' => 'raw_material',
                        'gray' => 'packaging',
                    ])
                    ->searchable(),
            ])
            ->filters([
                selectFilter::make('type')
                    ->options([
                        'raw_material' => 'Raw Material',
                        'packaging' => 'Packaging',
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
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
