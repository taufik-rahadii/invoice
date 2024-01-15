<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Store extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ["name", "address", "organization_id", "phone", "is_active"];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
