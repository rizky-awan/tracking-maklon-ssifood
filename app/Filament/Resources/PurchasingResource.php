<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchasingResource\Pages;
use App\Filament\Resources\PurchasingResource\RelationManagers;
use App\Models\Purchasing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PurchasingResource extends Resource
{
    protected static ?string $model = Purchasing::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
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
            || auth()->user()->role === 'purchasing';
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

                Forms\Components\Select::make('raw_material')
                    ->options([
                        'UNAVAILABLE'=>'UNAVAILABLE',
                        'PURCHASE'=>'PURCHASE',
                        'LEAD TIME'=>'LEAD TIME',
                        'AVAILABLE'=>'AVAILABLE'
                    ]),
                Forms\Components\Select::make('price_1st_packaging')
                    ->options([
                        'RFQ SENT'=>'RFQ SENT',
                        'WAITING VENDOR'=>'WAITING VENDOR',
                        'QUOTE RECEIVED'=>'QUOTE RECEIVED',
                        'PRICE REVIEW'=>'PRICE REVIEW',
                        'WITHOUT'=>'WITHOUT',
                        'PRICE CONFIRMED'=>'PRICE CONFIRMED'
                    ]),
                Forms\Components\Select::make('price_2nd_packaging')
                    ->options([
                        'RFQ SENT'=>'RFQ SENT',
                        'WAITING VENDOR'=>'WAITING VENDOR',
                        'QUOTE RECEIVED'=>'QUOTE RECEIVED',
                        'PRICE REVIEW'=>'PRICE REVIEW',
                        'WITHOUT'=>'WITHOUT',
                        'PRICE CONFIRMED'=>'PRICE CONFIRMED'
                    ]),

                Forms\Components\Toggle::make('dummy_1') ->label('Req Dummy 1st'),
                Forms\Components\Toggle::make('dummy_2') ->label('Req Dummy 2nd'),

                Forms\Components\Select::make('approve_dummy_1')->options([
                    'WAITING'=>'WAITING','APPROVE'=>'APPROVE'
                ]),
                Forms\Components\Select::make('approve_dummy_2')->options([
                    'WAITING'=>'WAITING','APPROVE'=>'APPROVE'
                ]),

                Forms\Components\Select::make('final_design')->options([
                    'WAITING'=>'WAITING','SUBMIT'=>'SUBMIT'
                ]),
                Forms\Components\Select::make('po_status')->options([
                    'WAITING'=>'WAITING','PURCHASE ORDER'=>'PURCHASE ORDER'
                ]),
                Forms\Components\Select::make('printing_approve')
                    ->options([
                        'ON REVIEW'=>'ON REVIEW',
                        'CONTENT REVISION'=>'CONTENT REVISION',
                        'COLOR REVISION'=>'COLOR REVISION',
                        'SIZE REVISION'=>'SIZE REVISION',
                        'APPROVE'=>'APPROVE'
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

                Tables\Columns\TextColumn::make('raw_material')->badge() ->alignCenter() ->label('Raw Material'),
                Tables\Columns\TextColumn::make('price_1st_packaging')->badge() ->alignCenter() ->label('Price 1st Packaging'),
                Tables\Columns\TextColumn::make('price_2nd_packaging')->badge() ->alignCenter() ->label('Price 2nd Packaging'),

                Tables\Columns\IconColumn::make('dummy_1')->boolean() ->alignCenter() ->label('Req Dummy 1st'),
                Tables\Columns\IconColumn::make('dummy_2')->boolean() ->alignCenter() ->label('Req Dummy 2nd'),

                Tables\Columns\TextColumn::make('approve_dummy_1')->badge() ->alignCenter() ->label('Approve Dummy 1st'),
                Tables\Columns\TextColumn::make('approve_dummy_2')->badge() ->alignCenter() ->label('Approve Dummy 2nd'),
                Tables\Columns\TextColumn::make('final_design')->badge() ->alignCenter() ->label('Final Design'),
                Tables\Columns\TextColumn::make('po_status')->badge() ->alignCenter() ->label('PO Status'),
                Tables\Columns\TextColumn::make('printing_approve')->badge() ->alignCenter(),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress %')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->state(fn ($record) => round($record->clientMaster->purchasing_progress, 0)),
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
            'index' => Pages\ListPurchasings::route('/'),
            'create' => Pages\CreatePurchasing::route('/create'),
            'edit' => Pages\EditPurchasing::route('/{record}/edit'),
        ];
    }
}
