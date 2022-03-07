<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mails extends Model
{
    use HasFactory;

    protected $table = 'password_resets';

    public $fillable = ['email','token','created_at'];

    public $timestamps = false;

    public $incrementing = false;
}
