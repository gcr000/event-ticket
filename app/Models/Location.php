<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function events ()
    {
        return $this->hasMany(Event::class)->orderBy('events.datetime_from', 'desc');
    }
}
