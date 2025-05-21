<?php

namespace App\Models;

use App\Http\Middleware\Master;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
