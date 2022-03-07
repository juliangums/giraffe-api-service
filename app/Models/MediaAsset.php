<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MediaAsset extends Model
{
    use HasFactory;

    protected $table = 'MEDIAASSET';

    public $timestamps = false;

    protected $primaryKey = 'ID';

    protected $fillable = [
        'PARAMETERS', 'HASHCODE', 'UUID', 'REVISION', 'CONTENTHASH', 'VALIDIMAGEFORIA', 'METADATA_MEDIAASSETMETADATA_ID_OID', 'STORE_ID_OID',
    ];

    protected $casts = [
        'PARAMETERS' => 'array',
    ];

    public function annotation()
    {
        return $this->hasOne(Annotation::class, 'MEDIAASSET_ID_OID');
    }

    public function occurrences(): BelongsToMany
    {
        return $this->belongsToMany(Occurrence::class, 'OCCURRENCE_ASSETS', 'ID_EID', 'OCCURRENCEID_OID');
    }
}
