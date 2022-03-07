<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Encounter model class.
 *
 * @property double $DECIMALLATITUDE
 * @property mixed $DECIMALLONGITUDE
 */
class Encounter extends Model
{
    use HasFactory;
    use Uuids;

    protected $table = 'ENCOUNTER';

    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    const CREATED_AT = 'DWCDATEADDED';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = 'MODIFIED';

    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'CATALOGNUMBER';

    protected $fillable = [
        'CATALOGNUMBER', 'DATEINMILLISECONDS', 'DAY', 'DISTINGUISHINGSCAR', 'DWCDATEADDED', 'DWCDATEADDEDLONG', 'DWCIMAGEURL',
        'GPSLATITUDE', 'GPSLONGITUDE', 'DECIMALLONGITUDE', 'DECIMALLATITUDE', 'GENUS', 'GUID', 'HOUR', 'LIVINGSTATUS', 'LOCATIONID',
        'MMACOMPATIBLE', 'MODIFIED', 'MONTH', 'MINUTES', 'SIZE_GUESS', 'SPECIFICEPITHET', 'RESEARCHERCOMMENTS', 'STATE', 'SUBMITTERID',
        'VERBATIMLOCALITY', 'YEAR', 'SEX', 'OCCURRENCEID', 'OKEXPOSEVIATAPIRLINK',
    ];

    protected $casts = [
        'DECIMALLONGITUDE' => 'double',
        'DECIMALLATITUDE' => 'double',
    ];

    /**
     * Pivot table for Encounter and Annotations
    */
    public function annotations(): BelongsToMany
    {
        return $this->belongsToMany(Annotation::class, 'ENCOUNTER_ANNOTATIONS', 'CATALOGNUMBER_OID', 'ID_EID');
    }

    /**
     * Pivot for Encounter and User
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ENCOUNTER_INFORMOTHERS', 'CATALOGNUMBER_OID', 'UUID_EID')->withPivot('UUID_EID', 'CATALOGNUMBER_OID');
    }
    /**
     * Pivot for user table and Encounter
     */
    public function submitted(): BelongsToMany
    {
        return $this->belongsToMany(Encounter::class, 'ENCOUNTER_SUBMITTERS', 'CATALOGNUMBER_OID', 'UUID_EID')->withPivot('UUID_EID', 'CATALOGNUMBER_OID');
    }

    public function occurrences(): BelongsToMany
    {
        return $this->belongsToMany(Occurrence::class, 'OCCURRENCE_ENCOUNTERS', 'CATALOGNUMBER_EID', 'OCCURRENCEID_OID');
    }
}
