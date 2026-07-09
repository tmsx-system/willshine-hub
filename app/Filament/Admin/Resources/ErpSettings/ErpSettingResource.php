<?php

namespace App\Filament\Admin\Resources\ErpSettings;

use App\Filament\Admin\Resources\ErpSettings\Pages\CreateErpSetting;
use App\Filament\Admin\Resources\ErpSettings\Pages\EditErpSetting;
use App\Filament\Admin\Resources\ErpSettings\Pages\ListErpSettings;
use App\Filament\Admin\Resources\ErpSettings\Schemas\ErpSettingForm;
use App\Filament\Admin\Resources\ErpSettings\Tables\ErpSettingsTable;
use App\Models\ErpSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ErpSettingResource extends Resource
{
    protected static ?string $model = ErpSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ErpSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ErpSettingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListErpSettings::route('/'),
            'create' => CreateErpSetting::route('/create'),
            'edit' => EditErpSetting::route('/{record}/edit'),
        ];
    }
}
