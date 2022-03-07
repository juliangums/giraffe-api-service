<?php

namespace App\Models;

use App\Traits\HasProfileImage;
use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileImage extends Model
{
    use HasFactory, Uuids;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = [
        'FULLFILESYSTEMPATH',
        'FILENAME',
        'CORRESPONDINGUSERNAME',
        'DATACOLLECTIONEVENTID',
        'WEBURL',
    ];

    protected $table = 'SINGLEPHOTOVIDEO';

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

    public function user()
    {
        return $this->hasOne(User::class);
    }

    protected static function booted()
    {
        static::creating(function (ProfileImage  $profileImage) {
            $event = DataCollectionEvent::query()->create([
                'TYPE' => 'SinglePhotoVideo',
            ]);
            $profileImage->DATACOLLECTIONEVENTID = $event->DATACOLLECTIONEVENTID;
        });
    }
}
