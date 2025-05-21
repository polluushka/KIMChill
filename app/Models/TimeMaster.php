<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeMaster extends Model
{
    protected $casts = [
        'date' => 'array'
    ];

    public function calendar()
    {
        return $this->belongsTo(Calendar::class);
    }
}
