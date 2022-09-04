<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institute extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'user_id', 'address', 'contact_no', 'code', 'image'
    ];

    ///Table name
   protected $table = 'institutes';

   //Primarykey
   public $primaryKey = 'id';

   //Timestamps
   public $timestamps = true;

   //link
   public function staff(){
       return $this->hasMany('App\Staff');
   }
   public function customers(){
    return $this->hasMany('App\Customer');
   }
   public function user(){
    return $this->belongsTo('App\User');
   }
   public function branches(){
    return $this->hasMany('App\Branch');
   }
   public function visits(){
    return $this->hasMany('App\Visit');
   }

}
