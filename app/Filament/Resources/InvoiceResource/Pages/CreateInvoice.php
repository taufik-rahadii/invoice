<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\InvoiceHistory;
use App\Models\InvoiceStatus;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Livewire\Attributes\Renderless;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function handleRecordCreation($data): Invoice
    {
        $attributes = $this->getAttributes()[0];

        $form = $attributes->getComponent()->data;
        $invoice = new Invoice();
        $invoice->seller_id = $form['seller_id'];
        $invoice->code = $form['code'];
        $invoice->no = $form['no'];
        $invoice->save();


        foreach ($form['details'] as $key => $vl) {
            $invoiceDetail = new InvoiceDetail();
            $invoiceDetail->invoice_id = $invoice->id;
            $invoiceDetail->product_id = $vl['product_id'];
            $invoiceDetail->product_price_id = $vl['product_price_id'];
            $invoiceDetail->qty = $vl['qty'];
            $invoiceDetail->total_price = (int) str_replace(["IDR ", ".", ","], "", $vl["total_price"]);
            $invoiceDetail->save();
        }

        $totalPayment = 0;

        foreach ($form['details'] as $key => $values) {
            $totalPayment += (int) str_replace(["IDR ", ".", ","], "", $values["total_price"]);
        }

        $invoiceHistory = new InvoiceHistory();
        $invoiceHistory->invoice_id = $invoice->id;
        $invoiceHistory->invoice_status_id = InvoiceStatus::query()->where('name', 'Nota Jalan')->first()->id;
        $invoiceHistory->remaining_total_payment = $totalPayment;
        $invoiceHistory->modified_by = auth()->user()->id;
        $invoiceHistory->save();
        return $invoice;
    }

    // protected function handleRecordCreation($data)
}
