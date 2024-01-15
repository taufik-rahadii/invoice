<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'description', 'product_unit_id', 'category_id', 'image'];

    public function unit_measurement(): BelongsTo
    {
        return $this->belongsTo(ProductUnitMeasurement::class, 'product_unit_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(ProductStock::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
