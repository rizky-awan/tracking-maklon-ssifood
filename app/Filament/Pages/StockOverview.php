<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Item;

class StockOverview extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Stock Management';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Stock Overview';
    protected static string $view = 'filament.pages.stock-overview';
    protected static ?int $navigationSort = 3;

    protected function getTableQuery()
    {
        return Item::query();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\Item::query()) // 🔥 INI WAJIB

            ->columns([

                TextColumn::make('name')
                    ->label('Item')
                    ->searchable(),

                TextColumn::make('code')
                    ->label('Code')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('producer')
                    ->label('Producer')
                    ->searchable(),

                TextColumn::make('stock')
                    ->label('Stock')
                    ->getStateUsing(fn ($record) => $record->getStock())
                    ->color(fn ($state, $record) =>
                        $state <= $record->min_stock ? 'danger' : 'success'
                    )
                    ->aligncenter(),

                TextColumn::make('stock_status')
                    ->label('Stock Status')
                    ->aligncenter()
                    ->getStateUsing(function ($record) {
                        $stock = $record->getStock();

                        if ($stock <= 0) return 'out';
                        if ($stock <= $record->min_stock) return 'low';

                        return 'safe';
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'out' => 'danger',
                        'low' => 'warning',
                        'safe' => 'success',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'out' => 'Out of Stock',
                        'low' => 'Low',
                        'safe' => 'Safe',
                    }),

                TextColumn::make('reserved')
                    ->getStateUsing(fn ($record) => $record->getReservedStock())
                    ->label('Reserved')
                    ->aligncenter(),

                TextColumn::make('available')
                    ->getStateUsing(fn ($record) => $record->getAvailableStock())
                    ->label('Available')
                    ->aligncenter(),

                TextColumn::make('expiry_date')
                    ->label('Expiry')
                    ->aligncenter()
                    ->getStateUsing(fn ($record) =>
                        $record->getBatchWithStatus()->first()?->expired_at
                    )
                    ->date(),

                TextColumn::make('expiry_status')
                    ->label('Expiry Status')
                    ->aligncenter()
                    ->getStateUsing(function ($record) {

                        // ❌ kalau ga track expiry → kosongin
                        if (!$record->track_expiry) {
                            return null;
                        }

                        $batch = $record->getBatchWithStatus()->first();
                        return $batch?->status ?? null;
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'expired' => 'danger',
                        'near_expiry' => 'warning',
                        'safe' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'expired' => 'Expired',
                        'near_expiry' => 'Near Expiry',
                        'safe' => 'Safe',
                        default => '',
                    }),
            ])
            ->filters([
                SelectFilter::make('stock_status')
                    ->label('Stock Status')
                    ->options([
                        'out' => 'Out of Stock',
                        'low' => 'Low',
                        'safe' => 'Safe',
                    ])
                    ->query(function ($query, $state) {

                        $value = is_array($state) ? $state['value'] ?? null : $state;

                        if (!$value) return $query;

                        return $query->where(function ($q) use ($value) {

                            if ($value === 'out') {
                                $q->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type="IN" THEN qty ELSE -qty END),0) 
                                    FROM stock_movements 
                                    WHERE stock_movements.item_id = items.id) <= 0');
                            }

                            if ($value === 'low') {
                                $q->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type="IN" THEN qty ELSE -qty END),0) 
                                    FROM stock_movements 
                                    WHERE stock_movements.item_id = items.id) > 0')
                                ->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type="IN" THEN qty ELSE -qty END),0) 
                                    FROM stock_movements 
                                    WHERE stock_movements.item_id = items.id) <= items.min_stock');
                            }

                            if ($value === 'safe') {
                                $q->whereRaw('(SELECT COALESCE(SUM(CASE WHEN type="IN" THEN qty ELSE -qty END),0) 
                                    FROM stock_movements 
                                    WHERE stock_movements.item_id = items.id) > items.min_stock');
                            }

                        });
                    }),

                SelectFilter::make('expiry_status')
                    ->label('Expiry Status')
                    ->options([
                        'expired' => 'Expired',
                        'near' => 'Near Expiry',
                        'safe' => 'Safe',
                    ])
                    ->query(function ($query, $state) {

                        $value = is_array($state) ? $state['value'] ?? null : $state;

                        if (!$value) return $query;

                        return $query->whereExists(function ($q) use ($value) {

                            $q->selectRaw(1)
                                ->from('stock_movements')
                                ->whereColumn('stock_movements.item_id', 'items.id');

                            if ($value === 'expired') {
                                $q->whereNotNull('expired_at')
                                ->where('expired_at', '<', now());
                            }

                            if ($value === 'near') {
                                $q->whereNotNull('expired_at')
                                ->whereBetween('expired_at', [now(), now()->addDays(30)]);
                            }

                            if ($value === 'safe') {
                                $q->whereNotNull('expired_at')
                                ->where('expired_at', '>', now()->addDays(30));
                            }

                        });
                    }),
            ]);
    }
}
