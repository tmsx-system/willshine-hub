<?php

namespace App\Filament\Admin\Resources\ErpItemPrices\Tables;

use App\Filament\Admin\Resources\Concerns\HasDateRangeFilters;
use App\Models\ErpItemPrice;
use App\Models\ErpSetting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ErpItemPricesTable
{
    use HasDateRangeFilters;

    private const FALLBACK_DEFAULT_PRICE_LIST = 'Standard Selling';

    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('item_code')
                    ->label('Kode Item')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('item.item_name')
                    ->label('Nama Item')
                    ->searchable(),
                TextColumn::make('price_list')
                    ->label('Daftar Harga')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                TextColumn::make('price_list_rate')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('uom')
                    ->label('Satuan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('valid_from')
                    ->label('Dari')
                    ->date()
                    ->sortable(),
                TextColumn::make('valid_upto')
                    ->label('Sampai')
                    ->date()
                    ->sortable(),
                TextColumn::make('last_synced_at')
                    ->label('Sinkron Terakhir')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('price_list')
                    ->label('Daftar Harga')
                    ->searchable()
                    ->options(fn (): array => ErpItemPrice::query()
                        ->whereNotNull('price_list')
                        ->distinct()
                        ->orderBy('price_list')
                        ->pluck('price_list', 'price_list')
                        ->all()),
                SelectFilter::make('uom')
                    ->label('Satuan')
                    ->searchable()
                    ->options(fn (): array => ErpItemPrice::query()
                        ->whereNotNull('uom')
                        ->distinct()
                        ->orderBy('uom')
                        ->pluck('uom', 'uom')
                        ->all()),
                Filter::make('default_price_list')
                    ->label('Hanya Harga Default Item')
                    ->query(function (Builder $query): Builder {
                        $defaultPriceList = ErpSetting::query()->value('default_selling_price_list')
                            ?: self::FALLBACK_DEFAULT_PRICE_LIST;

                        return $query->where('price_list', $defaultPriceList);
                    })
                    ->toggle(),
                self::dateRangeFilter('valid_from', 'Tanggal Berlaku'),
            ], layout: FiltersLayout::AboveContent)
            ->recordActions([
                EditAction::make()->label('Ubah'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Hapus Terpilih'),
                ]),
            ]);
    }
}
