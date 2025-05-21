<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    public function qualification()
    {
        return $this->belongsTo(Qualification::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function works()
    {
        return $this->hasMany(Work::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function master_services()
    {
        return $this->hasMany(MasterService::class);
    }

    public function services()
    {
        return $this->hasManyThrough(Service::class, MasterService::class);
    }
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
