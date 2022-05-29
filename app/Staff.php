<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
   use SoftDeletes;
   protected $fillable = [
    'user_id', 'institute_id', 'status', 'role'];

    ///Table name
   protected $table = 'staff';

   //Primarykey
   public $primaryKey = 'id';

   //Timestamps
   public $timestamps = true;

   //link
   public function institute(){
       return $this->belongsTo('App\Institute');
   }
   public function user(){
    return $this->belongsTo('App\User');
   }
}
