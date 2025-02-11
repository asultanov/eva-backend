<?php

namespace App\Models;

use App\Models\Guides\Medicament;
use Illuminate\Database\Eloquent\Model;

class GettingMedicament extends Model
{
    protected $guarded = [];
    public function medicament()
    {
        return $this->belongsTo(Medicament::class);
    }
}
