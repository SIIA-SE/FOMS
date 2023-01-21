<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Institute;
use App\Visit;
use App\Branch;
use App\Staff;
use Illuminate\Http\Request;
use App\Http\Requests\Customers\CreateCustomerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use DB;
use Carbon\Carbon;
use App\Province;
use App\District;
use App\DSDivision;
use App\GNDivision;
use Illuminate\Support\Facades\Gate;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($institute = Institute::find($request->id)){
        
            
            foreach($institute->staff as $staff){
                if($staff->user_id == Auth::id()){
                    if($staff->status == 1){
                        if($staff->role == 'frontdeskuser' || $staff->role == 'manager'){
                            //Return view
                            return view('customers.index')->with('institute', $institute)->with('staffRole', $staff->role);
                        }else{
                            session()->flash('message', 'You do not have permission to view the customers of the institute!');
                            session()->flash('alert-type', 'warning');

                            return redirect(route('institutes.index'));
                        }
                    }else{
                        session()->flash('message', 'You are not active staff of the institute!');
                        session()->flash('alert-type', 'warning');

                        return redirect(route('institutes.index'));
                    }
                }
            }
            
            session()->flash('message', 'You are not staff of the institute!');
            session()->flash('alert-type', 'warning');

            return redirect(route('institutes.index'));
            
            
        }
        else{
            session()->flash('message', 'Requested Institute is not available!');
            session()->flash('alert-type', 'danger');

            return redirect(route('institutes.index'));
        }

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
        if($institute = Institute::find($request->id)){
        
            
            foreach($institute->staff as $staff){
                if($staff->user_id == Auth::id()){
                    if($staff->status == 1){
                        if($staff->role == 'frontdeskuser' || $staff->role == 'manager'){
                            //Return View
                            return view('customers.create')->with('customer_inst', $institute)->with('staffRole', $staff->role);
                        }else {
                            session()->flash('message', 'You do not have permission to create customers of the institute!');
                            session()->flash('alert-type', 'warning');

                            return redirect(route('institutes.index'));
                        }
                    }
                    else{
                        session()->flash('message', 'You are not active staff of the institute!');
                        session()->flash('alert-type', 'warning');

                        return redirect(route('institutes.index'));
                    }
                }
            }

            session()->flash('message', 'You are not staff of the institute!');
            session()->flash('alert-type', 'warning');

            return redirect(route('institutes.index'));


        }else{
            session()->flash('message', 'Requested Institute is not available!');
            session()->flash('alert-type', 'danger');

            return redirect(route('institutes.index'));
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCustomerRequest $request)
    {
        foreach(Institute::find($request->institute_id)->staff as $staff){
            if($staff->user_id == Auth::user()->id){
                $staffRole = $staff->role;
            }
        }
        
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

        

        return view('customers.create')->with('customer_inst', Institute::find($request->institute_id))->with('staffRole', $staffRole);
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
                    if($staff->role == 'frontdeskuser' || $staff->role == 'manager'){
                    //Return view
                    return view('customers.index')->with('customer', $customer)->with('institute', $customer->institute)->with('visits', Customer::find($customer->id)->visits()->orderBy('created_at', 'DESC')->get())->with('staffRole', $staff->role);
                    }
                    else{
                        session()->flash('message', 'You do not have permission to view customers of the institute!');
                        session()->flash('alert-type', 'warning');

                        return redirect(route('institutes.index'));
                    }
                }
                else{
                    session()->flash('message', 'You are not active staff of the institute!');
                    session()->flash('alert-type', 'warning');

                    return redirect(route('institutes.index'));
                }
            }
        }
        session()->flash('message', 'You are not staff of the institute!');
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
        //Show edit page
        foreach(Institute::find($customer->institute->id)->staff as $staff){
            if($staff->user_id == Auth::id()){
                if($staff->status == 1){
                    if($staff->role == 'frontdeskuser' || $staff->role == 'manager'){
                    //Return view
                    return view('customers.create')->with('customer', $customer)->with('customer_inst', $customer->institute)->with('visits', Customer::find($customer->id)->visits()->orderBy('created_at', 'DESC')->get())->with('staffRole', $staff->role);
                    }
                    else{
                        session()->flash('message', 'You do not have permission to edit customers of the institute!');
                        session()->flash('alert-type', 'warning');

                        return redirect(route('customers.index'))->with('institute', Institute::find($customer->institute->id))->with('staffRole', $staff->role);
                    }
                }
                else{
                    session()->flash('message', 'You are not active staff of the institute!');
                    session()->flash('alert-type', 'warning');

                    return redirect(route('institutes.index'));
                }
            }
        }
        session()->flash('message', 'You are not staff of the institute!');
        session()->flash('alert-type', 'warning');

        return redirect(route('institutes.index'));
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

        foreach(Institute::find($customer->institute->id)->staff as $staff){
            if($staff->user_id == Auth::id()){
                if($staff->status == 1){
                    if($staff->role == 'frontdeskuser' || $staff->role == 'manager'){

                        //Update Customer Data to DB
                        $customer = Customer::find($customer->id);
        
                        $customer->first_name = $request->firstname;
                        $customer->last_name = $request->lastname;
                        $customer->gender = $request->gender;
                        $customer->nic_no = $request->nic_no;
                        $customer->address = $request->address;
                        $customer->contact_no = $request->contact_no;
                        $customer->email = $request->email;
                        $customer->province = $request->province;
                        $customer->district = $request->district;
                        $customer->ds_division = $request->dsdivision;
                        $customer->gn_division = $request->gndivision;
                        $customer->save();

                        session()->flash('message', 'Customer Data has been updated Successfully!');
                        session()->flash('alert-type', 'success');

                        return view('customers.create')->with('customer', $customer)->with('customer_inst', Institute::find($customer->institute->id))->with('visits', Customer::find($customer->id)->visits()->orderBy('created_at', 'DESC')->get())->with('staffRole', $staff->role);
                    }
                    else{
                        session()->flash('message', 'You do not have permission to update customers of the institute!');
                        session()->flash('alert-type', 'warning');

                        return redirect(route('customers.index'))->with('institute', Institute::find($customer->institute->id))->with('staffRole', $staff->role);
                    }
                }
                else{
                    session()->flash('message', 'You are not active staff of the institute!');
                    session()->flash('alert-type', 'warning');

                    return redirect(route('institutes.index'));
                }
            }
            
        }
        session()->flash('message', 'You are not staff of the institute!');
        session()->flash('alert-type', 'warning');

        return redirect(route('institutes.index'));
        
        
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
