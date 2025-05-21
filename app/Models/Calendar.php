<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function time_masters()
    {
        return $this->hasMany(TimeMaster::class);
    }
}
