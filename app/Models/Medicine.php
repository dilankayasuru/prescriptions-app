<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = [
        'name',
        'dosage',
        'unit_price',
        'stock',
    ];

    public function medicineQuotations()
    {
        return $this->hasMany(MedicineQuotation::class);
    }
}
