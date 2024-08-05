<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Yadahan\AuthenticationLog\AuthenticationLogable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;



class User extends Authenticatable implements LaratrustUser,JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable,HasRolesAndPermissions,AuthenticationLogable;
    use LogsActivity;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'photo',
        'whatshapp_no',
        'channel_id',
        'all_supplier',
        'status',
        'link_whatshapps',
        'api_key_whatshapps',
        'link_sync',
        'phone_number',
        'address',
        'latitude',
        'longitude',
        'about_us',
        'rating',
        'last_activity',
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



    public static $rules = array(
        'username' => 'username|required|unique:users,id'
    );

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function settings_user(){
        return $this->hasOne(SettingsUser::class,'user_id','id');
    }

    public function jabatan(){
        return $this->hasOne(Jabatan::class,'id','kode_jabatan');
    }

    public function department(){
        return $this->hasOne(Department::class,'id','kode_dept');
    }
    public function cabang(){
        return $this->hasOne(Province::class,'id','kode_cabang');
    }
    public function siswa(){
        return $this->hasOne(Siswa::class,'nis','nik');
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                ->logOnly([
                    'username',
                    'name',
                    'email',
                    'password',
                    'photo',
                    'whatshapp_no',
                    'channel_id',
                    'channel_id',
                    'all_supplier',
                    'status',
                    'link_whatshapps',
                    'api_key_whatshapps',
                    'link_sync',
                ])
                ->setDescriptionForEvent(fn(string $eventName) => "This user has been {$eventName}")
                ->useLogName('User');
    }

    public function incrementLoginAttempts(){
        $this->increment('login_attempts');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
