<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Institute;
use App\Http\Requests\Branches\CreateBranchRequest;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
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
                    //Return page
                    return view('branches.index')->with('institute', Institute::find($request->id));
                }else{
                    session()->flash('message', 'You do not have permission to view branches of the institute!');
                    session()->flash('alert-type', 'warning');

                    return redirect(route('institutes.index'));
                }
            }
        }
        
        session()->flash('message', 'You do not have permission to view branches of the institute!');
        session()->flash('alert-type', 'warning');

        return redirect(route('institutes.index'));
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
    public function store(CreateBranchRequest $request)
    {
        //Save Branch
        Branch::create([
            'institute_id' => $request->institute_id,
            'name' => $request->name,
            'branch_head' => $request->branch_head
        ]);

        session()->flash('message', 'Branch has been created Successfully!');
        session()->flash('alert-type', 'success');

        return view('branches.index')->with('institute', Institute::find($request->institute_id));
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
