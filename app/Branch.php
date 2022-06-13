<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'institute_id', 'branch_head'
    ];

     ///Table name
   protected $table = 'branches';

   //Primarykey
   public $primaryKey = 'id';

   //Timestamps
   public $timestamps = true;

   //link
   
   public function institute(){
    return $this->belongsTo('App\Institute');
   }
   
}
