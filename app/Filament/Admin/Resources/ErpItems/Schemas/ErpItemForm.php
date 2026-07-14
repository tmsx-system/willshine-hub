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
                Section::make('Identitas Item')
                    ->columns(2)
                    ->schema([
                        TextInput::make('erp_item_id')
                            ->label('ERP Item ID')
                            ->required(),
                        TextInput::make('item_code')
                            ->label('Kode Item')
                            ->required(),
                        TextInput::make('item_name')
                            ->label('Nama Item')
                            ->required(),
                        TextInput::make('item_group')
                            ->label('Grup Item'),
                        TextInput::make('stock_uom')
                            ->label('UOM Stok'),
                        TextInput::make('brand')
                            ->label('Brand'),
                        TextInput::make('company')
                            ->label('Company')
                            ->disabled()
                            ->dehydrated(),
                    ]),
                Section::make('Konten')
                    ->columns(2)
                    ->schema([
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        TextInput::make('image_url')
                            ->label('URL Gambar ERP')
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Sumber gambar dari ERP. Upload gambar frontend dilakukan satu tempat saja di Katalog Produk.'),
                    ]),
                Section::make('Status Inventory')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_stock_item')
                            ->label('Item Stok')
                            ->required(),
                        Toggle::make('disabled')
                            ->label('Nonaktif')
                            ->required(),
                        Toggle::make('has_batch_no')
                            ->label('Pakai Batch No')
                            ->required(),
                        Toggle::make('has_serial_no')
                            ->label('Pakai Serial No')
                            ->required(),
                    ]),
                Section::make('Info Sinkronisasi')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('erp_modified_at')
                            ->label('Diubah di ERP Pada'),
                        DateTimePicker::make('last_synced_at')
                            ->label('Sinkron Terakhir'),
                    ]),
            ]);
    }
}
