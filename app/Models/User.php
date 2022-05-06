<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;


    protected $guard_name = "api";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function video(){
        return $this->HasMany(Berita::class);
    }
    public function promotion(){
        return $this->HasMany(Promotion::class);
    }
    public function berita(){
        return $this->HasMany(Video::class);
    }
    public function image(){
        return $this->HasMany(Image::class);
    }
    public function article(){
        return $this->HasMany(Article::class);
    }

    public function getCreatedAtAttribute($created_at)
    {   
        $value = \Carbon\Carbon::parse($created_at);
        $parse = $value->locale('id');
        return $parse->translatedFormat('l, d F Y');
    }
    
    public function getUpdatedAtAttribute($updated_at)
    {   
        $value = \Carbon\Carbon::parse($updated_at);
        $parse = $value->locale('id');
        return $parse->translatedFormat('l, d F Y');
    }
    
    public function getJWTIdentifier(){
        return $this->getKey();
    }
    
    public function getJWTCustomClaims(){
        return [];
    }


}
