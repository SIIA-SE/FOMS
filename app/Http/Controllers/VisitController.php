<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Visits\CreateVisitRequest;
use Carbon\Carbon;
use App\Visit;
use App\Customer;
use App\Institute;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateVisitRequest $request)
    {
        $token = 0;
        if(Visit::latest()->first()){
            if(Carbon::now()->isSameDay(Visit::latest()->first()->created_at)){
                $token = sprintf('%03d', Visit::latest()->first()->token_no + 1);
            }else{
                $token = sprintf('%03d', $token + 1);
            }
        }else{
            $token = sprintf('%03d', $token + 1);
        }

        //dd(Carbon::now()->isSameDay(Visit::latest()->first()->created_at));
        
        //Save Visit
        $visit = Visit::create([
            'customer_id' => $request->custId,
            'branch_id' => $request->branch,
            'institute_id' => $request->institute_id,
            'purpose' => $request->purpose,
            'remarks' => $request->remarks,
            'token_no' => $token,
            'status' => "IN QUEUE",
        ]);

        session()->flash('message', 'Customer Visit has been created Successfully!');
        session()->flash('alert-type', 'success');

        return view('customers.create')->with('customer', Customer::find($request->custId))->with('customer_inst', Institute::find($request->institute_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
