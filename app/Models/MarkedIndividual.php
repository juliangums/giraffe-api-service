<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Queries\MarkedIndividualQuery;
use Illuminate\Support\Facades\Auth;

/**
 * MarkedIndividual model class.
 *
 * @property Collection<Encounter> $encounters
 */
class MarkedIndividual extends Model
{
    use HasFactory;
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'MARKEDINDIVIDUAL';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'INDIVIDUALID';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'DATETIMECREATED' => 'datetime:Y-m-d H:s:i',
    ];

    public function encounters(): BelongsToMany
    {
        return $this->belongsToMany(Encounter::class, 'MARKEDINDIVIDUAL_ENCOUNTERS', 'INDIVIDUALID_OID', 'CATALOGNUMBER_EID');
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param Builder $query
     */
    public function newEloquentBuilder($query): MarkedIndividualQuery
    {
        return new MarkedIndividualQuery($query);
    }

    /**
     * Determine whether a post has been marked as favorite by a user.
     *
     * @return boolean
     */
    public function favorited(): bool
    {
        return (bool) Favorite::where('UUID', Auth::id())
            ->where('INDIVIDUALID', $this->INDIVIDUALID)
            ->first();
    }

    public function getFavouriteAttribute(): bool
    {
        return $this->favorited();
    }
}
