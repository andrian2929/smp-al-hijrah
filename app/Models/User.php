<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'tanggal_lahir',
        'jenis_kelamin',
        'email',
        'password'
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
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function orangtua()
    {
        return $this->hasMany(Orangtua::class, 'user_id', 'id');
    }

    public function wali_kelas()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas_id', 'id');
    }

    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id', 'id');
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class, 'user_id', 'id')->with('kelas');
    }

    public function mutabaahYaumiyah()
    {
        return $this->hasMany(MutabaahYaumiyah::class, 'user_id', 'id');
    }
}
