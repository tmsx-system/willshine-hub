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
                Section::make('ERP Item')
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
                            ->label('Product Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('item_code')
                            ->label('Item Code')
                            ->disabled()
                            ->dehydrated(),
                        TextInput::make('item_name')
                            ->label('Item Name')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columnSpanFull(),

                Section::make('Catalog Display')
                    ->columns(2)
                    ->schema([
                        TextInput::make('display_name')
                            ->label('Display Name'),
                        TextInput::make('display_order')
                            ->label('Display Order')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Textarea::make('display_description')
                            ->label('Display Description')
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

                Section::make('Buyer Rules')
                    ->columns(2)
                    ->schema([

                        TextInput::make('minimum_qty')
                            ->label('Minimum Qty')
                            ->required()
                            ->numeric()
                            ->default(1),
                        TextInput::make('maximum_qty')
                            ->label('Maximum Qty')
                            ->numeric(),
                        Toggle::make('is_visible')
                            ->label('Visible')
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Featured')
                            ->required(),
                        Toggle::make('allow_decimal_qty')
                            ->label('Allow Decimal Qty')
                            ->required(),
                        Toggle::make('show_stock')
                            ->label('Show Stock')
                            ->required(),
                        Toggle::make('show_price')
                            ->label('Show Price')
                            ->required(),
                    ]),
            ]);
    }
}
