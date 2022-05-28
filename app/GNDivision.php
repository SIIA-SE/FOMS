<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GNDivision extends Model
{
    ////Table name
    protected $table = 'gndivisions';

    //Primarykey
    public $primaryKey = 'id';
 
    //Timestamps
    public $timestamps = true;
 
    //link
    public function dsdivision(){
        return $this->belongsTo('App\DSDivision');
    }
}
