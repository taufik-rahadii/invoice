<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceOverview;
use App\Models\InvoiceStatus;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return InvoiceResource::getWidgets();
    }

    public function getTabs(): array
    {
        $statusTabs = InvoiceStatus::all();

        $tabs = [
            'all' => Tab::make('Semua')
        ];

        foreach ($statusTabs as $statusTab) {
            $tabs[$statusTab->name] = Tab::make($statusTab->name);
        }

        return $tabs;
    }
}
