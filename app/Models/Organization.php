<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ["name", "logo", "phone", "email"];

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
