<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFilter extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];
    protected $casts = [
        'data' => 'array'
    ];

    protected function casts(): array
    {
        return ['data' => 'array'];
    }
}
