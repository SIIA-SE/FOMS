<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Institute;
use App\Customer;
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
        if($institute = Institute::find($request->id)){
        
            foreach($institute->staff as $staff){
                if($staff->user_id == Auth::id()){
                    if($staff->status == 1){
                        //Return page
                        return view('branches.index')->with('institute', Institute::find($request->id))->with('staffRole', $staff->role);
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
        
            
        }else{
            session()->flash('message', 'Requested Institute is not available!');
            session()->flash('alert-type', 'danger');

            return redirect(route('institutes.index'));
        }
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

        if($institute = Institute::find($request->institute_id)){
            foreach($institute->staff as $staff){
                if($staff->user_id == Auth::id()){
                    if($staff->status == 1){
                        return view('branches.index')->with('institute', Institute::find($request->institute_id))->with('staffRole', $staff->role);
                    }
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            
            $branch = Branch::find($request->branch);
            return json_encode($branch);
        }

        if($branch = Branch::find($request->branch)){
            
            $institute = Institute::find($branch->institute->id);

            foreach($institute->staff as $staff){
                if($staff->user_id == Auth::user()->id){
                    if($staff->status == 1){
                        //Return show page
                        if($staff->role == 'user'){
                            if($staff->branch_id == $branch->id){
                                $queue = Institute::find($branch->institute_id)->visits()->where('branch_id', $branch->id)->where('status', 'IN QUEUE')->orderBy('created_at', 'desc')->get();
                                return view('branches.show')->with('institute', $branch->institute)->with('branch', $branch)->with('queue', $queue)->with('staffRole', $staff->role);
                            }else{
                                session()->flash('message', 'You do not have permission to view this branch of the institute!');
                                session()->flash('alert-type', 'warning');

                                return redirect(route('branches.index'))->with('staffRole', $staff->role);
                            }
                        }else{
                            $queue = Institute::find($branch->institute_id)->visits()->where('branch_id', $branch->id)->where('status', 'IN QUEUE')->orderBy('created_at', 'desc')->get();
                            return view('branches.show')->with('institute', $branch->institute)->with('branch', $branch)->with('queue', $queue)->with('staffRole', $staff->role);
                        }
                        
                    }else {
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
            session()->flash('message', 'Requested branch is not avaialble!');
            session()->flash('alert-type', 'warning');

            return redirect(route('institutes.index'));
        }
        
    }


    function getQueue(Request $request)
    {
        
        if ($request->ajax()) {
            
            $branch = Branch::find($request->branch);
            $institute = Institute::find($branch->institute->id);
            
            $visits = Institute::find($branch->institute_id)->visits()->where('branch_id', $branch->id)->where('status', 'IN QUEUE')->orderBy('created_at', 'desc')->get();

            
            return json_encode($visits);
            
        }
    }

    function getServeList(Request $request)
    {
        if ($request->ajax()) {
            
            $branch = Branch::find($request->branch);
            $institute = Institute::find($branch->institute->id);
            
            $visits = Institute::find($branch->institute_id)->visits()->where('branch_id', $branch->id)->where('status', 'SERVING')->orderBy('created_at', 'asc')->get();

            
            return json_encode($visits);
            
        }
    }

    function getCustomerById(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::find($request->id);

            return json_encode($data);
        }
    }

    function display(Request $request)
    {
        if ($request->ajax()) {
            $branch = Branch::find($request->branch);
            $branch->display = $request->token;
            $branch->save();
        }

        return view('branches.display')->with('branch', Branch::find($request->branch));

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
    public function update(Request $request)
    {
        //uPDATE
        if($branch = Branch::find($request->branch)){
            $branch->name = $request->name;
            $branch->branch_head = $request->branch_head;
            $branch->save();

            session()->flash('message', 'Branch details has been updated successfully!');
            session()->flash('alert-type', 'success');

            foreach(Institute::find($branch->institute_id)->staff as $staff){
                if($staff->user_id == Auth::user()->id){
                    $staffRole = $staff->role;
                }
            }

            return redirect(route('branches.index', ['id' => $branch->institute_id]))->with('staffRole', $staff->role);
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
