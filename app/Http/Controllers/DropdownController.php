<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DropdownController extends Controller
{
    public function getDistrictsList(Request $request)
    {
        $districts = DB::table("districts")->where("province_id",$request->province_id)->pluck("name","id");
        
        //dd(response()->json($districts));
        
        return json_encode($districts);
    }

    public function getDSDivisionsList(Request $request)
    {
        $dsdivisions = DB::table("dsdivisions")->where("district_id",$request->district_id)->pluck("name","id");
        
        //dd(response()->json($districts));
        
        return json_encode($dsdivisions);
    }

    public function getGNDivisionsList(Request $request)
    {
        $gndivisions = DB::table("gndivisions")->where("ds_id",$request->dsdivision_id)->pluck("name","id");
        
        //dd(response()->json($districts));
        
        return json_encode($gndivisions);
    }


}
