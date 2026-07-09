<?php

namespace App\Filament\Admin\Resources\ProductCatalogs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductCatalogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_id')
                    ->required()
                    ->numeric(),
                TextInput::make('item_code'),
                TextInput::make('item_name'),
                TextInput::make('category_id')
                    ->numeric(),
                TextInput::make('display_name'),
                Textarea::make('display_description')
                    ->columnSpanFull(),
                FileUpload::make('display_image_url')
                    ->image(),
                Toggle::make('is_visible')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('display_order')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('minimum_qty')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('maximum_qty')
                    ->numeric(),
                Toggle::make('allow_decimal_qty')
                    ->required(),
                Toggle::make('show_stock')
                    ->required(),
                Toggle::make('show_price')
                    ->required(),
            ]);
    }
}
