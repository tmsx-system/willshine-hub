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
                Section::make('Kategori')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required(),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        FileUpload::make('image_url')
                            ->label('Gambar')
                            ->image()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Tampilan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('display_order')
                            ->label('Urutan Tampil')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
