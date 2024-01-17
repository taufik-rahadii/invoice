<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InvoiceOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::Make('Total Invoice', '10')
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('Gacor gan')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Total Piutang', '234000')->color('danger'),
            Stat::make('Invoice Jalan', '3')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
        ];
    }
}
