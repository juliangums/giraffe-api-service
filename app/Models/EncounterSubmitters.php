<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncounterSubmitters extends Model
{
    use HasFactory;

    protected $table = 'ENCOUNTER_SUBMITTERS';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $fillable = [
        'CATALOGNUMBER_OID',
        'UUID_EID',
        'IDX',
    ];
}
