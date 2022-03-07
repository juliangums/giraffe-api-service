<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncouunterMeasurements extends Model
{
    use HasFactory;

    protected $fillable = [
        'CATALOGNUMBER_OID',
        'DATACOLLECTIONEVENTID_EID',
        'IDX',
    ];

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

    protected $table = 'ENCOUNTER_MEASUREMENTS';
}
