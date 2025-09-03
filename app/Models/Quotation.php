<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'prescription_id',
        'status',
        'total_price',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medicineQuotations()
    {
        return $this->hasMany(MedicineQuotation::class);
    }
}
