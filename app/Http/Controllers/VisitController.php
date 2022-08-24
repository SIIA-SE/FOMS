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

        return redirect(route('customers.show', $request->custId));
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

    public function changeVisit(Request $request)
    {
        //dd($request->visitId);
        //Change Visit Status   
        if($visit = Visit::find($request->visitId)){
            
            if($request->visit_status == "serve"){
                $visit->status = "SERVING";
                $visit->save();

                session()->flash('message', 'Customer Serving has been started Successfully!');
                session()->flash('alert-type', 'success');

                return redirect(route('branches.show', $visit->branch_id));
            }
            

            if($request->visit_status == "return"){
                $visit->status = "RETURNED";
                $visit->save();

                session()->flash('message', 'Customer has been returned Successfully!');
                session()->flash('alert-type', 'success');

                return redirect(route('branches.show', $visit->branch_id));

            }
            

        }else{
            session()->flash('message', 'Requested Visit record not available');
            session()->flash('alert-type', 'warning');

            return redirect(route('home'));

        }
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
