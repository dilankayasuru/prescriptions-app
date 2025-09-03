<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'prescription_id',
        'file_path',
        'file_name',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
