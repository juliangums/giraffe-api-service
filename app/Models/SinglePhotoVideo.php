<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SinglePhotoVideo extends Model
{
    use HasFactory;

    protected $table = 'SINGLEPHOTOVIDEO';

    public $fillable = [
        'DATACOLLECTIONEVENTID',
        'COPYRIGHTOWNER',
        'COPYRIGHTSTATEMENT',
        'CORRESPONDINGSTORYID',
        'CORRESPONDINGUSERNAME',
        'FILENAME',
        'FULLFILESYSTEMPATH',
        'PATTERNINGPASSPORT_PATTERNINGPASSPORT_ID_OID',
        'WEBURL',
    ];

    public $primaryKey = false;

    public $incrementing = false;

    public $timestamps = false;
}
