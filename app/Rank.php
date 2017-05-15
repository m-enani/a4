<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rank extends Model
{
    public function choices()
    {
        return $this->belongsToMany('App\Choice')->withTimestamps();
    }
}
