<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function services()
    {
        return $this->hasManyThrough(Service::class, Price::class);
    }
}
