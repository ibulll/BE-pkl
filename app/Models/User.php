<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasPermissions;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPermissions;

    public function role()
    {
        return $this->belongsTo(Role::class,);
    }

    public function jurnal()
    {
        return $this->hasOne(Jurnal::class);
    }

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
        'role_id'
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

    protected $attributes = [
        'role_id' => 4, // Ganti nilai default sesuai kebutuhan
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
