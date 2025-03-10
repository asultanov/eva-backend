<?php

namespace App\Models;

use App\Models\Guides\Therapy;
use Illuminate\Database\Eloquent\Model;

class GettingTherapy extends Model
{
    protected $guarded = [];
    public function therapy()
    {
        return $this->belongsTo(Therapy::class);
    }
}
