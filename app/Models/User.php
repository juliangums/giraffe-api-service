<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Collection<MarkedIndividual> $favorites
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'FULLNAME',
        'EMAILADDRESS',
        'HASHEDEMAILADDRESS',
        'PASSWORD',
        'ACCEPTEDUSERAGREEMENT',
        'RECEIVEEMAILS',
        'SALT',
        'USERIMAGE_DATACOLLECTIONEVENTID_OID',
        'LASTLOGIN',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'PASSWORD',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'USERS';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'UUID';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (User $user) {
            $user->LASTLOGIN = -1;
        });
    }

    public function profileImage(): BelongsTo
    {
        return $this->belongsTo(ProfileImage::class, 'USERIMAGE_DATACOLLECTIONEVENTID_OID', 'DATACOLLECTIONEVENTID');
    }

    /**
     * PIVOT for User table and Encounter table
     */
    public function capture(): BelongsToMany
    {
        return $this->belongsToMany(Encounter::class, 'ENCOUNTER_INFORMOTHERS', 'UUID_EID', 'CATALOGNUMBER_OID')->withPivot('UUID_EID', 'CATALOGNUMBER_OID');
    }

    /**
     * Pivot for user table and Encounter
     */
    public function submitters()
    {
        return $this->belongsToMany(Encounter::class, 'ENCOUNTER_SUBMITTERS', 'UUID_EID', 'CATALOGNUMBER_OID')->withPivot('UUID_EID', 'CATALOGNUMBER_OID');
    }

    /**
     * Get all of favorite posts for the user.
     */
    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(MarkedIndividual::class, 'favorites', 'UUID', 'INDIVIDUALID')->withTimeStamps();
    }
}
