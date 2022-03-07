<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncounterInformOthers extends Model
{
    use HasFactory;

    protected $table = 'ENCOUNTER_INFORMOTHERS';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'CATALOGNUMBER_OID',
        'UUID_EID',
        'IDX',
    ];
}
