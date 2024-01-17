<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $fillable = [
        'seller_id',
        'code',
        'no',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(InvoiceDetail::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(InvoiceHistory::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
