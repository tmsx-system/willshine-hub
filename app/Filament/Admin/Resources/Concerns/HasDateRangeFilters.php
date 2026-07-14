<?php

namespace App\Filament\Admin\Resources\Concerns;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

trait HasDateRangeFilters
{
    protected static function dateRangeFilter(
        string $column = 'created_at',
        string $label = 'Tanggal'
    ): Filter {
        return Filter::make($column . '_range')
            ->label($label)
            ->schema([
                DatePicker::make('from')
                    ->label('Dari Tanggal'),
                DatePicker::make('until')
                    ->label('Sampai Tanggal'),
            ])
            ->query(fn (Builder $query, array $data): Builder => $query
                ->when($data['from'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate($column, '>=', $date))
                ->when($data['until'] ?? null, fn (Builder $query, string $date): Builder => $query->whereDate($column, '<=', $date)));
    }
}
