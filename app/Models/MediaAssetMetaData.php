<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaAssetMetaData extends Model
{
    use HasFactory;

    protected $table = 'MEDIAASSETMETADATA';

    public $timestamps = false;

    protected $primaryKey = null;

    public $incrementing = false;

    protected $guarded = [];
}
