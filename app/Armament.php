<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Armament extends Model {
    
    public function spaceship() {
    	return $this->belongsTo(Spaceship::class);
    }
}
