<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'customer_id', 'branch_id', 'institute_id', 'purpose', 'remarks', 'token_no', 'status', 'start_time', 'end_time'
    ];

     ///Table name
   protected $table = 'visits';

   //Primarykey
   public $primaryKey = 'id';

   //Timestamps
   public $timestamps = true;

   //link
   
   
   public function customer(){
    return $this->belongsTo('App\Customer');
   }
   public function institute(){
    return $this->belongsTo('App\Institute');
   }
   
}
