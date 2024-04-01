<?php

namespace App\Models;

use App\Casts\LatLong;
use Faker\Core\Coordinates;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    const TYPES = [
        'Traits',
        'Diagnoses',
        'Family',
        'Personal',
        'Vocation',
        'Notable',
        'Lifestyle'
    ];

    protected function casts(): array
{
    return [
        'coordinates' => LatLong::class,
        'birth_date' => 'datetime:d-m-Y h:i:s',
    ];
}

    public function properties()
    {
        return $this->hasMany(EntityProperties::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    protected function latlong(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strtolower($value),
        );
    }


}
