<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCollectionEvent extends Model
{
    use HasFactory;
    use Uuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = [
        'TYPE',
    ];

    protected $table = 'DATACOLLECTIONEVENT';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'DATACOLLECTIONEVENTID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
