<?php

namespace App\Filament\Admin\Resources\ErpItems\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ErpItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('erp_item_id')
                    ->required(),
                TextInput::make('item_code')
                    ->required(),
                TextInput::make('item_name')
                    ->required(),
                TextInput::make('item_group'),
                TextInput::make('stock_uom'),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('brand'),
                FileUpload::make('image_url')
                    ->image(),
                FileUpload::make('website_image_url')
                    ->image(),
                Toggle::make('is_stock_item')
                    ->required(),
                Toggle::make('disabled')
                    ->required(),
                Toggle::make('has_batch_no')
                    ->required(),
                Toggle::make('has_serial_no')
                    ->required(),
                DateTimePicker::make('erp_modified_at'),
                DateTimePicker::make('last_synced_at'),
            ]);
    }
}
