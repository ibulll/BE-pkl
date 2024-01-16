<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'api';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getPermissionArray(){
        return $this->getAllPermissions()->
        mapWithkeys(function($pr){
            return [$pr['name'] => true];
        });
    }
    /**
     * getJWTIdentifier
     * 
     * @return void
      */ 


      public function getJWTIdentifier()
      {
          return $this->getKey();
      }
  
      /**
       * getJWTCustomClaims
       * 
       * @return void
        */ 
  
  
      public function getJWTCustomClaims()
      {
         return [];
      }
}
