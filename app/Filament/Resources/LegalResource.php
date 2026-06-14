<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LegalResource\Pages;
use App\Filament\Resources\LegalResource\RelationManagers;
use App\Models\Legal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class LegalResource extends Resource
{
    protected static ?string $model = Legal::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
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
            || auth()->user()->role === 'legal';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    // INFO
                Forms\Components\TextInput::make('brand_name')->disabled(),
                Forms\Components\TextInput::make('client_name')->disabled(),
                Forms\Components\TextInput::make('product_type')->disabled(),
                Forms\Components\TextInput::make('variant')->disabled(),

                // PROCESS
                Forms\Components\Select::make('contract_kirim')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]),
                Forms\Components\Select::make('contract_terima')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]),
                Forms\Components\Select::make('lab_test')->options([
                    'SUBMITED'=>'SUBMITED',
                    'REVISION'=>'REVISION',
                    'NO TESTED'=>'NO TESTED',
                    'DONE'=>'DONE'
                ]),
                Forms\Components\Select::make('ingredients')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]),
                Forms\Components\Select::make('nutrition_fact')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]),
                Forms\Components\Select::make('checking_label')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]),
                Forms\Components\Select::make('status_legal')->options([
                    'DRAFTING'=>'DRAFTING',
                    'SUBMIT'=>'SUBMIT',
                    'SPB INVOICE'=>'SPB INVOICE',
                    'EVALUATION'=>'EVALUATION',
                    'REVISION'=>'REVISION',
                    'NIE PASS'=>'NIE PASS'
                ]),
                Forms\Components\Select::make('bpom')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]) ->label('NO BPOM SHARED'),

                Forms\Components\Select::make('barcode')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]) ->label('BARCODE SHARED'),

                Forms\Components\Select::make('status_label')->options([
                    'CHECKING'=>'CHECKING','REVISION'=>'REVISION','APPROVE'=>'APPROVE'
                ]),
                Forms\Components\Select::make('print1')->options([
                    'REVISION'=>'REVISION','WITHOUT'=>'WITHOUT','APPROVE'=>'APPROVE'
                ]) ->label('PRINTPROVE 1st'),
                Forms\Components\Select::make('print2')
                    ->options([
                        'REVISION'=>'REVISION','WITHOUT'=>'WITHOUT','APPROVE'=>'APPROVE'
                    ])
                    ->label('PRINTPROVE 2nd'),
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

                Tables\Columns\TextColumn::make('contract_kirim')->badge() ->alignCenter() ->label('Contract Kirim'),
                Tables\Columns\TextColumn::make('contract_terima')->badge() ->alignCenter() ->label('Contract Terima'),
                Tables\Columns\TextColumn::make('lab_test')->badge() ->alignCenter() ->label('Lab Test'),
                Tables\Columns\TextColumn::make('ingredients')->badge() ->alignCenter() ->label('Ingredients'),
                Tables\Columns\TextColumn::make('nutrition_fact')->badge() ->alignCenter() ->label('Nutrition Fact'),
                Tables\Columns\TextColumn::make('checking_label')->badge() ->alignCenter() ->label('Checking Label'),
                Tables\Columns\TextColumn::make('status_legal')->badge() ->alignCenter() ->label('Status Legal'),
                Tables\Columns\TextColumn::make('bpom')->badge() ->alignCenter() ->label('Shared BPOM'),
                Tables\Columns\TextColumn::make('barcode')->badge() ->alignCenter() ->label('Shared Barcode'),
                Tables\Columns\TextColumn::make('status_label')->badge() ->alignCenter() ->label('Status Label'),
                Tables\Columns\TextColumn::make('print1')->badge() ->alignCenter() ->label('PRINTPROVE 1st'),
                Tables\Columns\TextColumn::make('print2')->badge() ->alignCenter() ->label('PRINTPROVE 2nd'),

                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress %')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->state(fn ($record) => round($record->clientMaster->legal_progress, 0)),
            ])
            ->filters([
                SelectFilter::make('contract_kirim')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]),
                SelectFilter::make('contract_terima')->options([
                    'BELUM'=>'BELUM','SUDAH'=>'SUDAH'
                ]),
                SelectFilter::make('status_legal')->options([
                    'DRAFTING'=>'DRAFTING',
                    'SUBMIT'=>'SUBMIT',
                    'SPB INVOICE'=>'SPB INVOICE',
                    'EVALUATION'=>'EVALUATION',
                    'REVISION'=>'REVISION',
                    'NIE PASS'=>'NIE PASS'
                ]),
                SelectFilter::make('status_label')->options([
                    'CHECKING'=>'CHECKING','REVISION'=>'REVISION','APPROVE'=>'APPROVE'
                ]),
                SelectFilter::make('print1')->options([
                    'REVISION'=>'REVISION','WITHOUT'=>'WITHOUT','APPROVE'=>'APPROVE'
                ]) ->label('PRINTPROVE 1st'),
                SelectFilter::make('print2')
                    ->options([
                        'REVISION'=>'REVISION','WITHOUT'=>'WITHOUT','APPROVE'=>'APPROVE'
                    ])
                    ->label('PRINTPROVE 2nd'),
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
            'index' => Pages\ListLegals::route('/'),
            'create' => Pages\CreateLegal::route('/create'),
            'edit' => Pages\EditLegal::route('/{record}/edit'),
        ];
    }
}
