<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    public function ranks()
    {
        return $this->belongsToMany('App\Rank')->withTimestamps();
    }

}
