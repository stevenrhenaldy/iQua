<?php

namespace App\Models;

use App\Notifications\verify_email;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'email_verification_key',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_key',
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

    // Laravel Passport Authentication with Username or Email
    public function findForPassport($username) {
        return $this->where('username', $username)->orWhere('email', $username)->first();
    }

    public function groupsAll()
    {
        return $this->belongsToMany(Group::class, GroupUser::class)->whereNull('group_users.deleted_at');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, GroupUser::class)->whereNull('group_users.deleted_at')->whereNotNull('group_users.accepted_at');
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new verify_email);
    }
}
