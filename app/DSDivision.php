<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DSDivision extends Model
{
    ////Table name
    protected $table = 'dsdivisions';

    //Primarykey
    public $primaryKey = 'id';
 
    //Timestamps
    public $timestamps = true;
 
    //link
    public function district(){
        return $this->belongsTo('App\District');
    }
    public function gndivisions(){
        return $this->hasMany('App\GNDivision');
    }
}
