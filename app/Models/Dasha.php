<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dasha extends Model
{
    use HasFactory;


    protected $guarded = ['id'];

    public function entity() : BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function subDashas() : HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parentDasha() : BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }
}
