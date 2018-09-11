<?php

namespace App;

use App\Thread;
use App\Activity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Utils\RecordVisit;

class User extends Authenticatable
{
    use Notifiable, RecordVisit;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    protected $appends = [
        'visitsCount'
    ];

    protected $casts = [
        'confirmed' => 'boolean'
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activities()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function confirm()
    {
        $this->confirmed = true;
        $this->confirmation_token = null;
        $this->save();

        return $this;
    }

    public function isAdmin()
    {
        return Role::where('user_id', $this->id)
            ->where('name', 'admin')
            ->exists();
    }

    public function getAvatarPathAttribute($avatar_path)
    {
        if (!$avatar_path) {
            return '/storage/avatars/default_avatar.png';
        }

        return '/storage/' . $avatar_path;
    }

    public function getVisitsCountAttribute()
    {
        return $this->visits()->count();
    }
}
