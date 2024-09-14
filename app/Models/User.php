<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Core\Countries;
use App\Models\SystemCore\Users\UserTeams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method static where(string $string, mixed $username)
 * @method static create(array $all)
 */
class User extends Authenticatable{
    use HasFactory, Notifiable;

    /**
     * User roles
     */
    protected static array $_roles = [
        'administrator' => 'Administrator',
        'sys-mod' => 'System moderator',
        'league-mod' => 'League moderator',
        'user' => 'User'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'email_verified_at',
        'password',
        'api_token',
        'restart_pin',
        'birth_date',
        'city',
        'country',
        's_not',
        's_loc',
        's_b_date',
        'role',
        'active'
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

    /**
     * Helper functions and methods
     */

    public function birthDate():string {
        return Carbon::parse($this->birth_date)->format('d.m.Y');
    }
    public static function getRoles(): array{ return self::$_roles; }

    /**
     * Model relationships
     */
    public function teamsRel(): HasOne{
        return $this->hasOne(UserTeams::class, 'user_id', 'id');
    }
    public function countryRel(): HasOne{
        return $this->hasOne(Countries::class, 'id', 'country');
    }
    public function prefixRel(): HasOne{
        return $this->hasOne(Countries::class, 'id', 'prefix');
    }
}
