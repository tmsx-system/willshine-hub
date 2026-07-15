<?php

namespace App\Filament\Admin\Resources\ErpItemPrices\Schemas;

use App\Models\ErpItem;
use App\Models\ErpSetting;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ErpItemPriceForm
{
    private const FALLBACK_DEFAULT_PRICE_LIST = 'Standard Selling';

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Harga Item')
                    ->columns(2)
                    ->schema([
                        TextInput::make('erp_price_id')
                            ->label('Kode Harga')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Otomatis dibuat dari kode item dan daftar harga. Bisa diubah jika diperlukan.'),
                        Select::make('item_code')
                            ->label('Item')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                $item = $state ? ErpItem::query()->where('item_code', $state)->first() : null;

                                if ($item?->stock_uom) {
                                    $set('uom', $item->stock_uom);
                                }

                                $set('erp_price_id', ErpItemPriceForm::generatedPriceId($state, $get('price_list')));
                            })
                            ->searchable()
                            ->options(fn (): array => ErpItem::query()
                                ->orderBy('item_name')
                                ->get()
                                ->mapWithKeys(fn (ErpItem $item): array => [
                                    $item->item_code => "{$item->item_code} - {$item->item_name}",
                                ])
                                ->all()),
                        Toggle::make('use_default_price_list')
                            ->label('Jadikan Harga Default Item')
                            ->dehydrated(false)
                            ->live()
                            ->default(true)
                            ->afterStateUpdated(function (Set $set, ?bool $state): void {
                                if ($state) {
                                    $set('price_list', ErpItemPriceForm::defaultPriceList());
                                }
                            })
                            ->helperText('Aktifkan ini jika harga dipakai sebagai fallback/default ketika customer tidak punya harga khusus.'),
                        TextInput::make('price_list')
                            ->label('Daftar Harga')
                            ->required()
                            ->live(onBlur: true)
                            ->default(fn (): string => ErpItemPriceForm::defaultPriceList())
                            ->afterStateHydrated(function (Set $set, Get $get, ?string $state): void {
                                if (blank($state)) {
                                    $set('price_list', ErpItemPriceForm::defaultPriceList());
                                }

                                if (blank($get('erp_price_id'))) {
                                    $set('erp_price_id', ErpItemPriceForm::generatedPriceId($get('item_code'), $state ?: ErpItemPriceForm::defaultPriceList()));
                                }
                            })
                            ->afterStateUpdated(function (Set $set, Get $get, ?string $state): void {
                                $set('erp_price_id', ErpItemPriceForm::generatedPriceId($get('item_code'), $state));
                            })
                            ->datalist(fn (): array => collect([
                                ErpItemPriceForm::defaultPriceList(),
                            ])
                                ->merge(ErpItemPriceForm::existingPriceLists())
                                ->filter()
                                ->unique()
                                ->values()
                                ->all())
                            ->helperText('Harga default item memakai Price List Penjualan Default dari ERP Settings. Jika setting kosong, sistem memakai Standard Selling. Harga khusus customer memakai price list customer/perusahaan.'),
                        TextInput::make('price_list_rate')
                            ->label('Harga')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp'),
                        TextInput::make('currency')
                            ->label('Mata Uang')
                            ->default('IDR')
                            ->maxLength(10),
                        TextInput::make('uom')
                            ->label('Satuan')
                            ->maxLength(255),
                        DatePicker::make('valid_from')
                            ->label('Berlaku Dari'),
                        DatePicker::make('valid_upto')
                            ->label('Berlaku Sampai'),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function existingPriceLists()
    {
        return \App\Models\ErpItemPrice::query()
            ->whereNotNull('price_list')
            ->distinct()
            ->orderBy('price_list')
            ->pluck('price_list');
    }

    private static function defaultPriceList(): string
    {
        return ErpSetting::query()->value('default_selling_price_list') ?: self::FALLBACK_DEFAULT_PRICE_LIST;
    }

    private static function generatedPriceId(?string $itemCode, ?string $priceList): ?string
    {
        if (blank($itemCode)) {
            return null;
        }

        $suffix = $priceList === self::defaultPriceList()
            ? 'DEFAULT'
            : self::slugCode($priceList ?: 'PRICE');

        return self::slugCode($itemCode) . '-' . $suffix;
    }

    private static function slugCode(?string $value): string
    {
        $code = strtoupper((string) $value);
        $code = preg_replace('/[^A-Z0-9]+/', '-', $code) ?: '';

        return trim($code, '-');
    }
}
