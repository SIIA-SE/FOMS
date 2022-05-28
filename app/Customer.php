<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'gender', 'nic_no', 'address', 'contact_no', 'province' , 'district', 'ds_division', 'gn_division'
    ];
}
