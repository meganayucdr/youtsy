<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Smartisan\Filters\Traits\Filterable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, Filterable, HasApiTokens;

    /** @var string Filter Class */
    protected $filters = 'App\Filters\UserFilter';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()  {
        return $this->belongsTo('App\User');
    }

    public function hollandTest()  {
        return $this->hasMany('App\HollandTest');
    }
}
