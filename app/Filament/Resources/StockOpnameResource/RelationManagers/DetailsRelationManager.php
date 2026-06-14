<?php

namespace App\Filament\Resources\StockOpnameResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('item_id')
                    ->relationship('item', 'name')
                    ->disabled(),

                Forms\Components\TextInput::make('system_qty')
                    ->numeric()
                    ->disabled(),

                Forms\Components\TextInput::make('physical_qty')
                    ->numeric()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {

                        $systemQty = (float) ($get('system_qty') ?? 0);

                        $set('difference', $state - $systemQty);
                    })
                    ->disabled(fn () =>
                        $this->ownerRecord->status === 'approved'
                    ),

                Forms\Components\TextInput::make('difference')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Textarea::make('notes'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item')
            ->columns([

                Tables\Columns\TextColumn::make('item.name')
                    ->label('Item')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->item->code . ' - ' . $record->item->name;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('system_qty') ->alignCenter(),
                Tables\Columns\TextColumn::make('physical_qty') ->alignCenter(),
                Tables\Columns\TextColumn::make('difference') ->alignCenter(),
                Tables\Columns\TextColumn::make('notes'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn () =>
                        $this->ownerRecord->status !== 'approved'
                    ), 
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
