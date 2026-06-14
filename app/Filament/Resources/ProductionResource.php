<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionResource\Pages;
use App\Filament\Resources\ProductionResource\RelationManagers;
use App\Models\Production;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class ProductionResource extends Resource
{
    protected static ?string $model = Production::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Production Management';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->role === 'admin'
            || auth()->user()->role === 'sales'
            || auth()->user()->role === 'r&d'
            || auth()->user()->role === 'legal'
            || auth()->user()->role === 'design'
            || auth()->user()->role === 'purchasing'
            || auth()->user()->role === 'production';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->role === 'admin'
            || auth()->user()->role === 'sales'
            || auth()->user()->role === 'r&d'
            || auth()->user()->role === 'legal'
            || auth()->user()->role === 'design'
            || auth()->user()->role === 'purchasing'
            || auth()->user()->role === 'production';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->role === 'admin'
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
                Forms\Components\TextInput::make('brand_name')->disabled(),
                Forms\Components\TextInput::make('client_name')->disabled(),
                Forms\Components\TextInput::make('product_type')->disabled(),
                Forms\Components\TextInput::make('variant')->disabled(),

                Forms\Components\Toggle::make('balanced'),
                Forms\Components\DatePicker::make('balanced_date'),

                Forms\Components\Toggle::make('mixing'),
                Forms\Components\DatePicker::make('mixing_date'),

                Forms\Components\Toggle::make('filling'),
                Forms\Components\DatePicker::make('filling_date'),

                Forms\Components\Toggle::make('packing'),
                Forms\Components\DatePicker::make('packing_date'),

                Forms\Components\Toggle::make('sending'),
                Forms\Components\DatePicker::make('sending_date'),
                
                Forms\Components\DatePicker::make('estimasi_ready'),

                Forms\Components\Toggle::make('client_receive'),

                Forms\Components\Select::make('status')
                ->options([
                    'RUNNING' => 'RUNNING',
                    'HOLDING' => 'HOLDING',
                    'CANCEL' => 'CANCEL',
                    'DONE' => 'DONE',
                ])
                ->default('RUNNING')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand_name') ->searchable(),
                Tables\Columns\TextColumn::make('client_name') ->searchable(),
                Tables\Columns\TextColumn::make('product_type') ->searchable(),
                Tables\Columns\TextColumn::make('variant') ->searchable(),

                Tables\Columns\TextColumn::make('balanced_date')->date(),
                Tables\Columns\IconColumn::make('balanced')->boolean(),
                Tables\Columns\TextColumn::make('mixing_date')->date(),
                Tables\Columns\IconColumn::make('mixing')->boolean(),
                Tables\Columns\TextColumn::make('filling_date')->date(),
                Tables\Columns\IconColumn::make('filling')->boolean(),
                Tables\Columns\TextColumn::make('packing_date')->date(),
                Tables\Columns\IconColumn::make('packing')->boolean(),
                Tables\Columns\TextColumn::make('sending_date')->date(),
                Tables\Columns\IconColumn::make('sending')->boolean(),
                Tables\Columns\IconColumn::make('client_receive')->boolean(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->alignCenter()
                    ->color(fn ($state) => match ($state) {
                        'RUNNING' => 'info',
                        'HOLDING' => 'warning',
                        'CANCEL' => 'danger',
                        'DONE' => 'success',
                    }),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress %')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->state(fn ($record) => round($record->clientMaster->production_progress, 0)),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'RUNNING' => 'RUNNING',
                        'HOLDING' => 'HOLDING',
                        'CANCEL' => 'CANCEL',
                        'DONE' => 'DONE',
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
            'index' => Pages\ListProductions::route('/'),
            'create' => Pages\CreateProduction::route('/create'),
            'edit' => Pages\EditProduction::route('/{record}/edit'),
        ];
    }
}
