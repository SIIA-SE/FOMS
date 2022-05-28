<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Requests\Customers\CreateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       //Return view
       return view('customers.index')->with('customers', Customer::all());
    }

    function autoComplete(Request $request)
    {
        if ($request->ajax()) {

            $data = Customer::where('nic_no','LIKE',$request->name.'%')->orWhere('first_name', 'LIKE', $request->name.'%')->get();

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Return View
        return view('customers.create');
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
        Customer::create([
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

        session()->flash('success', 'Customer Data has been added Successfully!');

        return redirect(route('customers.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //Return view
       return view('customers.index')->with('customer', $customer);
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
