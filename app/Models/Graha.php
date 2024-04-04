<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graha extends Model
{
    use HasFactory;


    protected $guarded = ['id'];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
    
}
