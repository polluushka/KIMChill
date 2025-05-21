<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function qualification()
    {
        return $this->belongsTo(Qualification::class);
    }
}
