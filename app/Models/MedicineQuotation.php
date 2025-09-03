<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineQuotation extends Model
{
    protected $fillable = [
        'quotation_id',
        'name',
        'dosage',
        'unit_price',
        'quantity',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
