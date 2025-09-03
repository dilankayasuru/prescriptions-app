<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'user_id',
        'note',
        'delivery_address',
        'delivery_time',
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }
}
