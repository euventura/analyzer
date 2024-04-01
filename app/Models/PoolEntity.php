<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoolEntity extends Model
{
    use HasFactory;

    const STATUS_DONE = 'done';
    const STATUS_PENDING = 'pending';

    const STATUS_CONTENT = 'content';

    const STATUS_INFO = 'info';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'info' => 'array',
        ];
    }
}
