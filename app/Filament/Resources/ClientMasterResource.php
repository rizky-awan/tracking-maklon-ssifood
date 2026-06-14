<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientMasterResource\Pages;
use App\Filament\Resources\ClientMasterResource\RelationManagers;
use App\Models\ClientMaster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;

class ClientMasterResource extends Resource
{
    protected static ?string $model = ClientMaster::class;

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-users';
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

    public static function canCreate(): bool
    {
        return auth()->user()->role === 'admin'
            || auth()->user()->role === 'sales';
    }

    public static function canEdit($record): bool
    {
        return auth()->user()->role === 'admin'
            || auth()->user()->role === 'sales'
            || auth()->user()->role === 'finance';
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            // 🔹 CLIENT INFO
            Forms\Components\Section::make('Client Info')
                ->schema([

                    Forms\Components\TextInput::make('brand_name')
                        ->required(),

                    Forms\Components\TextInput::make('client_name')
                        ->required(),

                    Forms\Components\TextInput::make('product_type')
                        ->required(),

                    Forms\Components\TextInput::make('variant')
                        ->required(),

                ])->columns(2),

            // 🔹 FLOW
            Forms\Components\Section::make('Flow')
                ->schema([

                    Forms\Components\Select::make('category')
                        ->options([
                            'NEW' => 'NEW',
                            'REPEAT' => 'REPEAT',
                        ])
                        ->required(),

                    Forms\Components\Select::make('payment_status')
                        ->options([
                            'UNPAYMENT' => 'UNPAYMENT',
                            'SAMPLE PAYMENT' => 'SAMPLE PAYMENT',
                            'FREE SAMPLE' => 'FREE SAMPLE',
                            'LAB, LEGAL + 25% DP' => 'LAB, LEGAL + 25% DP',
                            'DP 50%' => 'DP 50%',
                            'TERM OF PAYMENT' => 'TERM OF PAYMENT',
                            'FULL PAYMENT' => 'FULL PAYMENT',
                        ])
                        ->required(),

                    Forms\Components\Select::make('design_from')
                        ->options([
                            'SSI' => 'SSI',
                            'CLIENT' => 'CLIENT',
                            'HYBRID' => 'HYBRID',
                        ]),

                    Forms\Components\TextInput::make('pic'),

                ])->columns(2),

            // 🔹 PAYMENT DATE
            Forms\Components\Section::make('Payment Dates')
                ->schema([

                    Forms\Components\DatePicker::make('sample_payment_date'),

                    Forms\Components\DatePicker::make('lab_legal_dp_date')
                        ->label('Lab Legal + 25% DP'),

                    Forms\Components\DatePicker::make('dp_50_date'),

                    Forms\Components\DatePicker::make('full_payment_date'),

                ])->columns(2),

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
                // Tables\Columns\ViewColumn::make('card')
                //     ->view('filament.tables.columns.client-card'),

                Tables\Columns\TextColumn::make('brand_name')->searchable() ->label('Brand Name'),

                Tables\Columns\TextColumn::make('client_name') ->searchable()->label('Client Name'),

                Tables\Columns\TextColumn::make('product_type') ->searchable()->label('Product Type'),

                Tables\Columns\TextColumn::make('variant') ->searchable()->label('Variant'),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn ($state) => $state === 'NEW' ? 'success' : 'warning')
                    ->alignCenter()
                    ->label('Category'),

                Tables\Columns\TextColumn::make('payment_status')->badge() ->alignCenter()->label('Payment Status'),

                Tables\Columns\TextColumn::make('design_from')->badge() ->alignCenter()->label('Design From'),

                Tables\Columns\TextColumn::make('pic') ->label('PIC') ->alignCenter(),

                Tables\Columns\TextColumn::make('formula_progress')
                    ->label('Formula')
                    ->alignCenter()
                    ->formatStateUsing(fn ($record) => round($record->formula_progress) . '%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                                $state == 100 => 'success',
                                $state >= 50 => 'warning',
                                default => 'danger'}),

                Tables\Columns\TextColumn::make('legal_progress')
                    ->label('Legal')
                    ->alignCenter()
                    ->formatStateUsing(fn ($record) => round($record->legal_progress) . '%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                                $state == 100 => 'success',
                                $state >= 50 => 'warning',
                                default => 'danger'}),

                Tables\Columns\TextColumn::make('design_progress')
                    ->label('Design')
                    ->alignCenter()
                    ->formatStateUsing(fn ($record) => round($record->design_progress) . '%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                                $state == 100 => 'success',
                                $state >= 50 => 'warning',
                                default => 'danger'}),

                Tables\Columns\TextColumn::make('purchasing_progress')
                    ->label('Purchasing')
                    ->alignCenter()
                    ->formatStateUsing(fn ($record) => round($record->purchasing_progress) . '%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                                $state == 100 => 'success',
                                $state >= 50 => 'warning',
                                default => 'danger'}),

                Tables\Columns\TextColumn::make('production_progress')
                    ->label('Production')
                    ->alignCenter()
                    ->formatStateUsing(fn ($record) => round($record->production_progress) . '%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                                $state == 100 => 'success',
                                $state >= 50 => 'warning',
                                default => 'danger'}),

                Tables\Columns\TextColumn::make('total_progress')
                    ->label('Progress %')
                    ->alignCenter()
                    ->formatStateUsing(fn ($record) => round($record->total_progress) . '%')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                                $state == 100 => 'success',
                                $state >= 50 => 'warning',
                                default => 'danger'}),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->alignCenter()
                    ->color(fn ($state) => match ($state) {
                        'RUNNING' => 'info',
                        'HOLDING' => 'warning',
                        'CANCEL' => 'danger',
                        'DONE' => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'RUNNING' => 'RUNNING',
                        'HOLDING' => 'HOLDING',
                        'CANCEL' => 'CANCEL',
                        'DONE' => 'DONE',
                    ]),

                SelectFilter::make('payment_status')
                    ->options([
                        'UNPAYMENT' => 'UNPAYMENT',
                        'SAMPLE PAYMENT' => 'SAMPLE PAYMENT',
                        'FREE SAMPLE' => 'FREE SAMPLE',
                        'LAB, LEGAL + 25% DP' => 'LAB, LEGAL + 25% DP',
                        'DP 50%' => 'DP 50%',
                        'TERM OF PAYMENT' => 'TERM OF PAYMENT',
                        'FULL PAYMENT' => 'FULL PAYMENT',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                //Tables\Actions\Action::make('dashboard')
                    //->label('Dashboard')
                    //->url(fn ($record) => static::getUrl('dashboard', ['record' => $record]))
                    //->icon('heroicon-o-chart-bar'),
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
            'index' => Pages\ListClientMasters::route('/'),
            'create' => Pages\CreateClientMaster::route('/create'),
            'edit' => Pages\EditClientMaster::route('/{record}/edit'),
            'dashboard' => Pages\ClientDashboard::route('/{record}/dashboard'),
        ];
    }
}
