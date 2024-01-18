<?php

namespace App\Filament\Resources\InvoiceResource\Widgets;

use App\Models\Invoice;
use App\Models\InvoiceHistory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class InvoiceOverview extends BaseWidget
{

    public function convertToIDR($number)
    {
        // Format the number with two decimal places and use IDR as the currency symbol
        $formattedNumber = number_format($number, 2, ',', '.');

        // Add IDR as the currency symbol
        $idrString = 'Rp.  ' . $formattedNumber;

        return $idrString;
    }

    protected function getStats(): array
    {
        // $invoices = DB::table("invoice_histories")->join("invoices", "invoice_histories.invoice_id", "=", "invoice_id")
        //     ->join("invoice_statuses", "invoice_histories.invoice_status_id", "=", "invoice_statuses.id")
        //     ->where('invoice_statuses.name', '=', 'Nota Jalan')
        //     ->select(['invoices.*', 'invoice_statuses.*', 'invoice_histories.remaining_total_payment'])
        //     ->get();
        $ongoingInvoice = InvoiceHistory::query()->whereHas("status", function ($query) {
            $query->where('name', 'Nota Jalan');
        })->has('invoice')->count();

        $finishedInvoice = InvoiceHistory::query()->whereHas("status", function ($query) {
            $query->where('name', 'Nota Selesai');
        })->has('invoice')->count();

        $totalPayment = InvoiceHistory::query()->whereHas("status", function ($query) {
            $query->where('name', 'Nota Jalan');
        })->sum('remaining_total_payment');

        return [
            Stat::Make('Nota Jalan', $ongoingInvoice)
                ->color('warning')
                ->description('Nota Dengan Status Nota Jalan')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Total Piutang', $this->convertToIDR((int) $totalPayment))
                ->color('danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Nota Selesai', $finishedInvoice)
                ->description('Nota Dengan Status Selesai')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
        ];
    }
}
