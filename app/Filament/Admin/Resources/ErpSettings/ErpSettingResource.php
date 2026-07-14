<?php

namespace App\Filament\Admin\Resources\ErpSettings;

use App\Filament\Admin\Resources\Concerns\HasResourceNavigationBadge;
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
    use HasResourceNavigationBadge;

    protected static ?string $model = ErpSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static \UnitEnum|string|null $navigationGroup = 'ERP';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'ERP Settings';

    protected static ?string $modelLabel = 'ERP Setting';

    protected static ?string $pluralModelLabel = 'ERP Settings';

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
