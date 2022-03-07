<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Occurrence extends Model
{
    use HasFactory;
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'OCCURRENCE';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'OCCURRENCEID';

    public function encounters(): BelongsToMany
    {
        return $this->belongsToMany(Encounter::class, 'OCCURRENCE_ENCOUNTERS', 'OCCURRENCEID_OID', 'CATALOGNUMBER_EID');
    }

    public function mediaAssets(): BelongsToMany
    {
        return $this->belongsToMany(MediaAsset::class, 'OCCURRENCE_ASSETS', 'OCCURRENCEID_OID', 'ID_EID');
    }
}
