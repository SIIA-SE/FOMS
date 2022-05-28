<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    ///Table name
    protected $table = 'districts';

    //Primarykey
    public $primaryKey = 'id';
 
    //Timestamps
    public $timestamps = true;
 
    //link
    public function province(){
        return $this->belongsTo('App\Provine');
    }
    public function dsdivisions(){
        return $this->hasMany('App\DSDivision');
    }
}
