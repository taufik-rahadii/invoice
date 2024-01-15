<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['code', 'name', 'selling_price', 'product_id'];

    public function stockLocation(): BelongsTo
    {
        return $this->belongsTo(StockLocation::class);
    }
}
