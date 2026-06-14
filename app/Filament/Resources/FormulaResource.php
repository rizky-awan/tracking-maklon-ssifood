<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormulaResource\Pages;
use App\Filament\Resources\FormulaResource\RelationManagers;
use App\Models\Formula;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class FormulaResource extends Resource
{
    protected static ?string $model = Formula::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Project Management';

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
            || auth()->user()->role === 'r&d';
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

                Forms\Components\Select::make('formula_progress')
                    ->options([
                        'Queue' => 'Queue',
                        'Creating Formula Draft' => 'Creating Formula Draft',
                        'Internal Testing' => 'Internal Testing',
                        'Evaluate' => 'Evaluate',
                        'Create Improved Formula' => 'Create Improved Formula',
                        'Finalize COGS' => 'Finalize COGS',
                        'Ready' => 'Ready',
                    ]),

                Forms\Components\Select::make('availability')
                    ->options([
                        'UNAVAILABLE' => 'UNAVAILABLE',
                        'PURCHASE' => 'PURCHASE',
                        'LEAD TIME' => 'LEAD TIME',
                        'AVAILABLE' => 'AVAILABLE',
                    ]),

                Forms\Components\Select::make('status')
                    ->options([
                        'Creating Sample' => 'Creating Sample',
                        'Deliver to Client' => 'Deliver to Client',
                        'Revision Sample' => 'Revision Sample',
                        'Cancel' => 'Cancel',
                        'Sample Approove' => 'Sample Approove',
                    ]),

                Forms\Components\Select::make('cpb_status')
                    ->options([
                        'ON PROGRESS' => 'ON PROGRESS',
                        'DONE' => 'DONE',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand_name') ->searchable() ->label('Brand Name'),
                Tables\Columns\TextColumn::make('client_name') ->searchable() ->label('Client Name'),
                Tables\Columns\TextColumn::make('product_type') ->searchable() ->label('Product Type'),
                Tables\Columns\TextColumn::make('variant') ->searchable() ->label('Variant'),

                Tables\Columns\TextColumn::make('formula_progress')->badge() ->alignCenter()->label('Formula Progress'),
                Tables\Columns\TextColumn::make('availability')->badge() ->label('RAW Sample') ->alignCenter(),
                Tables\Columns\TextColumn::make('status')->badge() ->alignCenter(),
                Tables\Columns\TextColumn::make('cpb_status')->badge() ->label('CPB Status') ->alignCenter(),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress %')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->state(fn ($record) => round($record->clientMaster->formula_progress, 0)),
            ])
            ->filters([
                SelectFilter::make('formula_progress')
                    ->options([
                        'Queue' => 'Queue',
                        'Creating Formula Draft' => 'Creating Formula Draft',
                        'Internal Testing' => 'Internal Testing',
                        'Evaluate' => 'Evaluate',
                        'Create Improved Formula' => 'Create Improved Formula',
                        'Finalize COGS' => 'Finalize COGS',
                        'Ready' => 'Ready',
                    ]),
                SelectFilter::make('availability')
                    ->options([
                        'UNAVAILABLE' => 'UNAVAILABLE',
                        'PURCHASE' => 'PURCHASE',
                        'LEAD TIME' => 'LEAD TIME',
                        'AVAILABLE' => 'AVAILABLE',
                    ]),
                SelectFilter::make('status')
                    ->options([
                        'Creating Sample' => 'Creating Sample',
                        'Deliver to Client' => 'Deliver to Client',
                        'Revision Sample' => 'Revision Sample',
                        'Cancel' => 'Cancel',
                        'Sample Approove' => 'Sample Approove',
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
            'index' => Pages\ListFormulas::route('/'),
            'create' => Pages\CreateFormula::route('/create'),
            'edit' => Pages\EditFormula::route('/{record}/edit'),
        ];
    }
}
