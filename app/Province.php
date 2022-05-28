<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    
   ///Table name
   protected $table = 'provinces';

   //Primarykey
   public $primaryKey = 'id';

   //Timestamps
   public $timestamps = true;

   //link
   public function districts(){
       return $this->hasMany('App\District');
   }
}
