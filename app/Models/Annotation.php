<?php

namespace App\Models;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\Uuids;

class Annotation extends Model
{
    use HasFactory;
    use Uuids;
    use HasRelationships;

    protected $table = 'ANNOTATION';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    public $fillable = [
        'ID', 'HEIGHT', 'ISEXEMPLAR', 'MATCHAGAINST', 'SPECIES', 'THETA', 'WIDTH', 'X', 'Y', 'MEDIAASSET_ID_OID', 'IDENTIFICATIONSTATUS',
    ];

    public function encounters(): BelongsToMany
    {
        return $this->belongsToMany(Annotation::class, 'ENCOUNTER_ANNOTATIONS', 'CATALOGNUMBER_OID', 'ID_EID');
    }

    public function mediaAsset(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'MEDIAASSET_ID_OID');
    }

    public function markedIndividuals(): BelongsToMany
    {
        return $this->belongsToMany(MarkedIndividual::class, 'MARKEDINDIVIDUAL_ENCOUNTERS', 'CATALOGNUMBER_EID', 'INDIVIDUALID_OID');
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class, 'ANNOTATION_FEATURES', 'ID_OID', 'ID_EID');
    }
    public function media(): \Staudenmeir\EloquentHasManyDeep\HasManyDeep
    {
        return $this->hasManyDeepFromRelations($this->features(), (new Feature())->mediaAssets());
    }

}
