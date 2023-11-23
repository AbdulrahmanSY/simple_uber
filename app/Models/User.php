<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use \App\Models\Driver;
use \App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'login_code',
    ];
    protected $hidden = [
        'login_code',
        'remember_token',
        'created_at',
        "updated_at"
];





    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

     public function driver(){
        return $this->hasOne(Driver::class);
     }
     public function trips(){
        return $this->hasMany(Trip::class);
     }

}
