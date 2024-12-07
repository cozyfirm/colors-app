<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Core\Countries;
use App\Models\Core\MyFile;
use App\Models\Social\Fans\Fan;
use App\Models\SystemCore\LeagueModerator;
use App\Models\SystemCore\Users\UserTeams;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method static create(array $all)
 * @method static where(string $string, string $string1, $api_token)
 * @method static inRandomOrder()
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
        'photo',
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

    public function checkRole($role) : bool{
        if($role === 'administrator'){
            return $this->role === 'administrator';
        }else if($role === 'sys-mod'){
            return ($this->role === 'administrator' or $this->role === 'sys-mod');
        }else if($role === 'league-mod'){
            return ($this->role === 'administrator' or $this->role === 'sys-mod' or $this->role === 'league-mod');
        }
    }
    public function getModeratorLeagues(){
        return LeagueModerator::where('user_id', $this->id)->get(['league_id']);
    }

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
    public function photoRel(): HasOne{
        return $this->hasOne(MyFile::class, 'id', 'photo');
    }

    /**
     *  Fans relationships
     */
    public function myFans(): HasMany{
        return $this->hasMany(Fan::class, 'user_id', 'id');
    }
    public function fansRel(): HasManyThrough{
        return $this->hasManyThrough(User::class, Fan::class, 'fan_id', 'id', 'id', 'user_id');
    }
}
