<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function master_services()
    {
        return $this->hasMany(MasterService::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, MasterService::class);
    }

    public function qualifications()
    {
        return $this->hasManyThrough(Qualification::class, Price::class);
    }
}
