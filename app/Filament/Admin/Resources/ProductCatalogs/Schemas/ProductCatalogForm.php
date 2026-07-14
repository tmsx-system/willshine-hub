<?php

namespace App\Filament\Admin\Resources\ProductCatalogs\Schemas;

use App\Models\ErpItem;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductCatalogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item ERP')
                    ->columns(2)
                    ->schema([
                        Select::make('item_id')
                            ->label('ERP Item')
                            ->relationship('item', 'item_name')
                            ->getOptionLabelFromRecordUsing(fn(ErpItem $record): string => "{$record->item_code} - {$record->item_name}")
                            ->searchable(['item_code', 'item_name'])
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?int $state): void {
                                $item = $state ? ErpItem::find($state) : null;

                                $set('item_code', $item?->item_code);
                                $set('item_name', $item?->item_name);
                                $set('display_name', $item?->item_name);
                                $set('display_description', $item?->description);
                                $set('display_image_url', $item?->image_url);
                            }),
                        Select::make('category_id')
                            ->label('Kategori Produk')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('item_code')
                            ->label('Kode Item')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('item_name')
                            ->label('Nama Item')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columnSpanFull(),

                Section::make('Tampilan Katalog')
                    ->columns(2)
                    ->schema([
                        TextInput::make('display_name')
                            ->label('Nama Tampil'),
                        TextInput::make('display_order')
                            ->label('Urutan Tampil')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Textarea::make('display_description')
                            ->label('Deskripsi Tampil')
                            ->columnSpanFull(),
                        // FileUpload::make('display_image_url')
                        //     ->label('Display Image')
                        //     ->disk('public')
                        //     ->directory('product-catalogs')
                        //     ->visibility('public')
                        //     ->image()
                        //     ->imageEditor()
                        //     ->openable()
                        //     ->downloadable()
                        //     ->getUploadedFileUsing(function (BaseFileUpload $component, string $file, string|array|null $storedFileNames): ?array {
                        //         if (str_starts_with($file, 'http://') || str_starts_with($file, 'https://')) {
                        //             return [
                        //                 'name' => basename(parse_url($file, PHP_URL_PATH) ?: $file),
                        //                 'size' => 0,
                        //                 'type' => 'image/*',
                        //                 'url' => $file,
                        //             ];
                        //         }

                        //         $storage = $component->getDisk();

                        //         try {
                        //             if (! $storage->exists($file)) {
                        //                 return null;
                        //             }

                        //             return [
                        //                 'name' => is_array($storedFileNames) ? ($storedFileNames[$file] ?? basename($file)) : ($storedFileNames ?? basename($file)),
                        //                 'size' => $storage->size($file),
                        //                 'type' => $storage->mimeType($file),
                        //                 'url' => $storage->url($file),
                        //             ];
                        //         } catch (\Throwable) {
                        //             return null;
                        //         }
                        //     }),
                    ]),

                Section::make('Aturan Buyer')
                    ->columns(2)
                    ->schema([

                        TextInput::make('minimum_qty')
                            ->label('Minimal Qty')
                            ->required()
                            ->numeric()
                            ->default(1),
                        TextInput::make('maximum_qty')
                            ->label('Maksimal Qty')
                            ->numeric(),
                        Toggle::make('is_visible')
                            ->label('Tampil di Buyer')
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Produk Unggulan')
                            ->required(),
                        Toggle::make('allow_decimal_qty')
                            ->label('Boleh Qty Desimal')
                            ->required(),
                        Toggle::make('show_stock')
                            ->label('Tampilkan Stok')
                            ->required(),
                        Toggle::make('show_price')
                            ->label('Tampilkan Harga')
                            ->required(),
                    ]),
            ]);
    }
}
