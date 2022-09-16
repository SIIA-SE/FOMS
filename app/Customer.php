<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'institute_id', 'first_name', 'last_name', 'gender', 'nic_no', 'address', 'contact_no', 'province' , 'district', 'ds_division', 'gn_division'
    ];

    ///Table name
   protected $table = 'customers';

   //Primarykey
   public $primaryKey = 'id';

   //Timestamps
   public $timestamps = true;

   protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

   //link
   
   public function institute(){
    return $this->belongsTo('App\Institute');
   }
   public function visits(){
    return $this->hasMany('App\Visit');
   }
}
