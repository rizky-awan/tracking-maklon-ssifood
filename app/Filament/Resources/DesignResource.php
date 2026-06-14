<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DesignResource\Pages;
use App\Filament\Resources\DesignResource\RelationManagers;
use App\Models\Design;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class DesignResource extends Resource
{
    protected static ?string $model = Design::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
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
            || auth()->user()->role === 'design';
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

                Forms\Components\Toggle::make('design_option'),
                Forms\Components\Toggle::make('create_mockup'),
                Forms\Components\Toggle::make('review_client'),

                Forms\Components\Select::make('design_1st_packaging')
                    ->options([
                        'ON PROGRESS'=>'ON PROGRESS',
                        'ON REVIEW'=>'ON REVIEW',
                        'REVISION'=>'REVISION',
                        'NOT USE/FROM CLIENT'=>'NOT USE/FROM CLIENT',
                        'CLIENT APPROVE'=>'CLIENT APPROVE'
                    ]),
                Forms\Components\Select::make('design_2nd_packaging')
                    ->options([
                        'ON PROGRESS'=>'ON PROGRESS',
                        'ON REVIEW'=>'ON REVIEW',
                        'REVISION'=>'REVISION',
                        'NOT USE/FROM CLIENT'=>'NOT USE/FROM CLIENT',
                        'CLIENT APPROVE'=>'CLIENT APPROVE'
                    ]),
                Forms\Components\Select::make('regulator_status')
                    ->options([
                    'CHECKING'=>'CHECKING',
                    'REVISION'=>'REVISION',
                    'WAITING MD/PIRT'=>'WAITING MD/PIRT',
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

                Tables\Columns\IconColumn::make('design_option')->boolean() ->alignCenter() ->label('Design Option'),
                Tables\Columns\IconColumn::make('create_mockup')->boolean() ->alignCenter() ->label('Create Mockup'),
                Tables\Columns\IconColumn::make('review_client')->boolean() ->alignCenter() ->label('Review Client'),

                Tables\Columns\TextColumn::make('design_1st_packaging')->badge() ->alignCenter() ->label('1st Packaging'),
                Tables\Columns\TextColumn::make('design_2nd_packaging')->badge() ->alignCenter() ->label('2nd Packaging'),
                Tables\Columns\TextColumn::make('regulator_status')->badge() ->label('Legal Status') ->alignCenter(),

                // Tables\Columns\TextColumn::make('progress')
                //     ->label('Progress %')
                //     ->alignCenter()
                //     ->badge()
                //     ->color('success'),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress %')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->state(fn ($record) => round($record->clientMaster->design_progress, 0)),
            ])
            ->filters([
                SelectFilter::make('regulator_status')
                    ->options([
                        'CHECKING'=>'CHECKING',
                        'REVISION'=>'REVISION',
                        'WAITING MD/PIRT'=>'WAITING MD/PIRT',
                        'APPROVE'=>'APPROVE'
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
            'index' => Pages\ListDesigns::route('/'),
            'create' => Pages\CreateDesign::route('/create'),
            'edit' => Pages\EditDesign::route('/{record}/edit'),
        ];
    }
}
