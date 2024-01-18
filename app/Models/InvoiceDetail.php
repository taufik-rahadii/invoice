<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceDetail extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['invoice_id', 'product_id', 'product_price_id', 'qty', 'total_price'];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productPrice(): BelongsTo
    {
        return $this->belongsTo(ProductPrice::class, 'product_price_id');
    }

    protected static function booted(): void
    {
        static::creating(function (InvoiceDetail $invoiceDetail) {
            $invoiceDetail->total_price = (int) str_replace(["IDR ", ".", ","], "", $invoiceDetail->total_price);
        });
    }
}
