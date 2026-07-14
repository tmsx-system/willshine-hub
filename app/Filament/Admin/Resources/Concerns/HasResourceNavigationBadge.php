<?php

namespace App\Filament\Admin\Resources\Concerns;

use Illuminate\Support\Facades\Schema;

trait HasResourceNavigationBadge
{
    public static function getNavigationBadge(): ?string
    {
        $model = static::getModel();

        if (!$model) {
            return null;
        }

        $table = (new $model())->getTable();

        if (!Schema::hasTable($table)) {
            return null;
        }

        $query = $model::query();
        $column = static::getNavigationBadgeColumn();

        if (
            $column
            && Schema::hasColumn($table, $column)
        ) {
            $query->where($column, static::getNavigationBadgeValue());
        }

        $count = $query->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'gray';
    }

    protected static function getNavigationBadgeColumn(): ?string
    {
        return null;
    }

    protected static function getNavigationBadgeValue(): mixed
    {
        return true;
    }
}
