<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class DetailInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('edit')
                ->url(route('invoices.edit', ['invoice' => $this->record])),
            Action::make('delete')
                ->requiresConfirmation()
                ->action(fn() => $this->post->delete())
        ];
    }
}
