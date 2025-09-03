<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicineQuotation extends Model
{
    protected $fillable = [
        'quotation_id',
        'medicine_id',
        'price',
        'quantity',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
