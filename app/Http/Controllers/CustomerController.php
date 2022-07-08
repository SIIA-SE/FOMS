<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Institute;
use App\Visit;
use App\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\Customers\CreateCustomerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use DB;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $institute = Institute::find($request->id);
        
        foreach($institute->staff as $staff){
            if($staff->user_id == Auth::id()){
                if($staff->status == 1){
                    //Return view
                    return view('customers.index')->with('institute', $institute);
                }else{
                    session()->flash('message', 'You do not have permission to view customers of the institute!');
                    session()->flash('alert-type', 'warning');

                    return redirect(route('institutes.index'));
                }
            }
        }
        
        session()->flash('message', 'You do not have permission to view customers of the institute!');
        session()->flash('alert-type', 'warning');

        return redirect(route('institutes.index'));
    
       
    }

    function autoComplete(Request $request)
    {
        if ($request->ajax()) {

            $inst = Institute::find($request->institute_id);

            $data = Institute::find($request->institute_id)->customers()->where(function (Builder $query) use ($request) {
                return $query->where('nic_no', 'LIKE', $request->name . '%')
                             ->orWhere('first_name', 'LIKE', $request->name . '%');
            })->get();

            /* $data = DB::table('institutes')
            ->join('customers', 'institutes.id', '=', 'customers.institute_id')
            ->where('institutes.id', '=', $request->institute_id)->where(function($query) use ($request) {
                $query->orWhere('customers.nic_no', 'LIKE', $request->name . '%')->orWhere('customers.first_name', 'LIKE', $request->name . '%');
            })
            ->get(); */

            $output = '';

            if (count($data)>0) {

                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';

                foreach ($data as $row) {

                    $output .= '<li class="list-group-item"><a href="' . route("customers.show", $row->id) . '">' .$row->first_name.' (' . $row->nic_no . ')</a></li>';

                }

                $output .= '</ul>';

            }else {

                $output .= '<li class="list-group-item">'.'No Data Found'.'</li>';

            }

            return $output;

        }

        return view('autosearch');  

    }

    function selectCustomer(Request $request)
    {
        if ($request->ajax()) {

            $inst = Institute::find($request->institute_id);

            $data = Institute::find($request->institute_id)->customers()->where(function (Builder $query) use ($request) {
                return $query->where('nic_no', 'LIKE', $request->name . '%')
                             ->orWhere('first_name', 'LIKE', $request->name . '%');
            })->get();

            /* $data = DB::table('institutes')
            ->join('customers', 'institutes.id', '=', 'customers.institute_id')
            ->where('institutes.id', '=', $request->institute_id)->where(function($query) use ($request) {
                $query->orWhere('customers.nic_no', 'LIKE', $request->name . '%')->orWhere('customers.first_name', 'LIKE', $request->name . '%');
            })
            ->get(); */

            $output = '';

            if (count($data)>0) {

                $output = '<ul class="list-group" style="display: block; position: relative; z-index: 1">';

                foreach ($data as $row) {

                    $output .= '<li class="list-group-item list-group-item-action" name="result"><a id="' . $row->id . '" name="result">' . $row->first_name . ' (' . $row->nic_no . ')</a></li>';

                }

                $output .= '</ul>';

            }else {

                $output .= '<li class="list-group-item">'.'No Data Found'.'</li>';

            }

            return $output;

        }

        return view('autosearch');  

    }

    function getCustomer(Request $request)
    {
        
   
        if ($request->ajax()) {
            
            $data = Institute::find($request->institute)->customers()->where('nic_no', $request->customer)->get();

            return json_encode($data);
        }
    }

    function getVisit(Request $request)
    {


        if ($request->ajax()) {
            
            $cust = Customer::find($request->customer);  

            foreach($cust->visits as $visit){
                if($visit->status == "SERVING"){
                    $branch = Branch::find($visit->branch_id);
                    
                    return json_encode(array('visit' => $visit, 'branch' => $branch, 'visit_time' => Carbon::parse($visit->created_at)->toFormattedDateString()));
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $institute = Institute::find($request->id);
        
        foreach($institute->staff as $staff){
            if($staff->user_id == Auth::id()){
                if($staff->status == 1){
                    //Return View
                    return view('customers.create')->with('customer_inst', $institute);
                }
                else{
                    session()->flash('message', 'You do not have permission to create customer of the institute!');
                    session()->flash('alert-type', 'warning');

                    return redirect(route('institutes.index'));
                }
            }
        }

        session()->flash('message', 'You do not have permission to create customer of the institute!');
        session()->flash('alert-type', 'warning');

        return redirect(route('institutes.index'));
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerRequest $request)
    {
        //Save New Customer Data to DB
        $customer = Customer::create([
            'institute_id' => $request->institute_id,
            'first_name' => $request->firstname,
            'last_name' => $request->lastname,
            'gender' => $request->gender,
            'nic_no' => $request->nic_no,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'email' => $request->email,
            'province' => $request->province,
            'district' => $request->district,
            'ds_division' => $request->dsdivision,
            'gn_division' => $request->gndivision,
        ]);

        session()->flash('message', 'Customer Data has been added Successfully!');
        session()->flash('alert-type', 'success');

        return view('customers.create')->with('customer_inst', Institute::find($request->institute_id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        foreach(Institute::find($customer->institute->id)->staff as $staff){
            if($staff->user_id == Auth::id()){
                if($staff->status == 1){
                    
                    //Return view
                    return view('customers.index')->with('customer', $customer)->with('institute', $customer->institute)->with('visits', Customer::find($customer->id)->visits()->orderBy('created_at', 'DESC')->get());
                }else{
                    session()->flash('message', 'You do not have permission to view the customer details!');
                    session()->flash('alert-type', 'warning');

                    return redirect(route('institutes.index'));
                }
            }
        }
        session()->flash('message', 'You do not have permission to view the customer details!');
        session()->flash('alert-type', 'warning');

        return redirect(route('institutes.index'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
