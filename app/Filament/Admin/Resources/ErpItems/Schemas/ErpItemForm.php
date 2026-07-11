<?php

namespace App\Filament\Admin\Resources\ErpItems\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ErpItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item Identity')
                    ->columns(2)
                    ->schema([
                        TextInput::make('erp_item_id')
                            ->label('ERP Item ID')
                            ->required(),
                        TextInput::make('item_code')
                            ->label('Item Code')
                            ->required(),
                        TextInput::make('item_name')
                            ->label('Item Name')
                            ->required(),
                        TextInput::make('item_group')
                            ->label('Item Group'),
                        TextInput::make('stock_uom')
                            ->label('Stock UOM'),
                        TextInput::make('brand'),
                        TextInput::make('company')
                            ->label('Company')
                            ->disabled()
                            ->dehydrated(),
                    ]),
                Section::make('Content')
                    ->columns(2)
                    ->schema([
                        Textarea::make('description')
                            ->columnSpanFull(),
                        TextInput::make('image_url')
                            ->label('ERP Image URL')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Sumber gambar dari ERP. Upload gambar frontend dilakukan satu tempat saja di Product Catalog > Display Image.'),
                    ]),
                Section::make('Inventory Flags')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_stock_item')
                            ->label('Stock Item')
                            ->required(),
                        Toggle::make('disabled')
                            ->label('Disabled')
                            ->required(),
                        Toggle::make('has_batch_no')
                            ->label('Has Batch No')
                            ->required(),
                        Toggle::make('has_serial_no')
                            ->label('Has Serial No')
                            ->required(),
                    ]),
                Section::make('Sync Metadata')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('erp_modified_at')
                            ->label('ERP Modified At'),
                        DateTimePicker::make('last_synced_at')
                            ->label('Last Synced At'),
                    ]),
            ]);
    }
}
