<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spaceship extends Model {

    public function armaments() {
    	return $this->hasMany(Armament::class);
    }

    protected $hidden = ['created_at','updated_at'];
    
}
