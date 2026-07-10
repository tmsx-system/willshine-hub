<?php

namespace App\Filament\Admin\Resources\ProductCategories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('slug')
                            ->required(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        FileUpload::make('image_url')
                            ->label('Image')
                            ->image(),
                    ]),
                Section::make('Display')
                    ->columns(2)
                    ->schema([
                        TextInput::make('display_order')
                            ->label('Display Order')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->required(),
                    ]),
            ]);
    }
}
