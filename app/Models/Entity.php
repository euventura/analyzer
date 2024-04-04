<?php

namespace App\Models;

use App\Casts\LatLong;
use Faker\Core\Coordinates;
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

    public function dashas()
    {
        return $this->hasMany(Dasha::class);
    }

    public function grahas()
    {
        return $this->hasMany(Graha::class);
    }

    public function bhavas()
    {
        return $this->hasMany(Bhava::class);
    }

}
