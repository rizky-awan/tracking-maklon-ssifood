<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkOrderResource\Pages;
use App\Filament\Resources\WorkOrderResource\RelationManagers;
use App\Models\WorkOrder;
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

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Production Management';

    protected static ?int $navigationSort = 2;

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
            ->disabled(fn ($record) => $record?->status === 'done')
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->default(fn () => 'WO-' . date('Ymd-His'))
                    ->label('WO Code')
                    ->placeholder('Auto / Manual')
                    ->maxLength(50),

                Forms\Components\Select::make('client_master_id')
                    ->label('Client')
                    ->options(
                        \App\Models\ClientMaster::all()->pluck('brand_name', 'id')
                    )
                    ->searchable(),  

                Forms\Components\TextInput::make('product_name')
                    ->label('Product')
                    ->maxLength(255),      

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'released' => 'Released',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ])
                    ->default('draft')
                    ->visible(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord),

                Repeater::make('items')
                    ->relationship()
                    ->schema([

                        Select::make('item_id')
                            ->relationship('item', 'name')
                            ->required(),

                        TextInput::make('qty_required')
                            ->numeric()
                            ->required(),

    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->label('ID'),

                Tables\Columns\TextColumn::make('code')
                    ->label('WO Code')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('clientMaster.brand_name')
                    ->label('Client')
                    ->searchable(), 

                Tables\Columns\TextColumn::make('product_name')
                    ->label('Product')
                    ->searchable(), 

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'released',
                        'info' => 'in_progress',
                        'success' => 'done',
                        'danger' => 'cancel',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
