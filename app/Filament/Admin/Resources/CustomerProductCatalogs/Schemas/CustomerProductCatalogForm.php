<?php

namespace App\Filament\Admin\Resources\CustomerProductCatalogs\Schemas;

use App\Models\ErpCustomer;
use App\Models\ProductCatalog;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CustomerProductCatalogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Customer Product Access')
                    ->columns(2)
                    ->schema([
                        Select::make('customer_id')
                            ->label('Customer')
                            ->relationship('customer', 'customer_name')
                            ->getOptionLabelFromRecordUsing(fn (ErpCustomer $record): string => "{$record->customer_code} - {$record->customer_name}")
                            ->searchable(['customer_code', 'customer_name'])
                            ->preload()
                            ->required(),
                        Select::make('product_catalog_id')
                            ->label('Product Catalog')
                            ->relationship('productCatalog', 'display_name')
                            ->getOptionLabelFromRecordUsing(fn (ProductCatalog $record): string => ($record->item_code ?: $record->item?->item_code ?: 'CAT-' . $record->id) . ' - ' . ($record->display_name ?: $record->item_name))
                            ->searchable(['item_code', 'item_name', 'display_name'])
                            ->preload()
                            ->required(),
                        TextInput::make('daily_quantity')
                            ->label('Daily Quantity')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->helperText('Qty maksimal yang dialokasikan untuk customer ini per hari.'),
                        TextInput::make('minimum_qty')
                            ->label('Minimum Order Qty')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Kosongkan untuk mengikuti minimum qty katalog.'),
                        TextInput::make('maximum_qty')
                            ->label('Maximum Order Qty')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Kosongkan untuk mengikuti maksimum qty katalog atau daily quantity.'),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->required()
                            ->default(true),
                        Textarea::make('note')
                            ->label('Internal Note')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
