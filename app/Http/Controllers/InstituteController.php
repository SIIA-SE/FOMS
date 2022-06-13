<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Institute;
use App\Staff;
use Illuminate\Support\Str;
use App\Http\Requests\Institutes\CreateInstituteRequest;
use App\Http\Requests\Institutes\UpdateInstituteRequest;
use Illuminate\Support\Facades\Auth;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userInstitutes = "";
        $joinedInstitutes = "";

        if(count(Auth::user()->institutes) > 0){
            $userInstitutes = Auth::user()->institutes;
        }
        if(count(Auth::user()->staff) > 0){
            $joinedInstitutes = Auth::user()->staff;
        }
        //Return view
        return view('institutes.index')->with('userInstitutes', $userInstitutes)->with('joinedInstitutes', $joinedInstitutes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('institutes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInstituteRequest $request)
    {
        
        if ($request->hasFile('image')) {
            if($request->file('image')->isValid()){
            
                $image = $request->image->store('institutes');
            }
        }
        else {
            $image = "institutes/default_institute.png";
        }
        
        $lastInstitute = Institute::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'code' => Str::random(4) . date('Ymd'),
            'image' => $image,

        ]);

        Staff::create([
            'user_id' => Auth::id(),
            'institute_id' => $lastInstitute->id,
            'status' => true,
            'role' => 'sys_admin'
        ]);

        session()->flash('message', 'Institute has been created Successfully!');
        session()->flash('alert-type', 'success');

        return redirect(route('institutes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //View Institute
        if($institute = Institute::find($id)){
            
            foreach($institute->staff as $staff){
                if($staff->user_id == Auth::id()){
                    if($staff->status == "2"){
                        session()->flash('message', 'Your request is to join the institute is pending..!');
                        session()->flash('alert-type', 'warning');
                        return redirect(route('institutes.index'));
                    }elseif($staff->status == "1"){
                        return view('institutes.show')->with('institute', $institute);
                    }
                    
                }
            }
            session()->flash('message', 'You are not in the staff of the institute!');
            session()->flash('alert-type', 'danger');
            return redirect(route('institutes.index'));

        }else{
            session()->flash('message', 'Requested institute is not available or Trashed!');
            session()->flash('alert-type', 'danger');
            return redirect(route('home'));
        }
        
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Institute $institute)
    {
        //Edit Institute
        if($institute){
            if(count(Auth::user()->institutes) > 0){
                foreach(Auth::user()->institutes as $userInstitute){
                    if($userInstitute->id == $institute->id){
                        return view('institutes.create')->with('institute', $institute);
                    }
                }
            }else if(count(Auth::user()->staff) > 0){
                foreach(Auth::user()->staff as $staff){
                    if($staff->institute_id == $institute->id){
                        if($staff->role == "sys_admin"){
                            return view('institutes.create')->with('institute', $institute);
                        }else{
                            session()->flash('message', 'You do not have permission to edit the institute!');
                            session()->flash('alert-type', 'warning');
                            return redirect(route('institutes.index'));
                        }
                    }    
                }
                session()->flash('message', 'You are not in the staff of the institute!');
                session()->flash('alert-type', 'danger');
                return redirect(route('institutes.index'));
            }else{
                session()->flash('message', 'You do not have permission to view the institute!');
                session()->flash('alert-type', 'warning');
                return redirect(route('home'));
            }
        }else{
            session()->flash('message', 'Requested institute is not available!');
            session()->flash('alert-type', 'danger');
            return redirect(route('home'));
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInstituteRequest $request, Institute $institute)
    {

        if ($request->hasFile('image')) {
            if($request->file('image')->isValid()){
            

                
                $image = $request->image->store('institutes');

            }
        }
        else{
            $image = "institutes/default_institute.png";
        }
        
        $institute->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'image' => $image,
        ]);


        session()->flash('success', 'Institute has been updated Successfully!');

        return redirect(route('institutes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $institute = Institute::withTrashed()->where('id', $id)->firstOrFail();
        
        if($institute->trashed()){

            $institute->forceDelete();

        }else{

            $institute->delete();

        }

        session()->flash('success', 'Institute has been deleted Successfully!');

        return redirect(route('institutes.index'));
    }

        /**
     * View trashed items  from storage.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {
        $trashed = Auth::user()->institutes()->onlyTrashed()->get();

       // dd(Auth::user()->institutes()->onlyTrashed()->get());
        return view('institutes.index')->with('trashedInstitutes', $trashed);
    }

    public function joinInstitute(Request $request)
    {
        if($request->institute_id_button == "join"){
                if($institute = Institute::firstWhere('code', $request->institute_id)){
                    if(!Staff::where('user_id', Auth::id())->where('institute_id', $request->institute_id)->get()){
                        Staff::create([
                            'user_id' => Auth::id(),
                            'institute_id' => $institute->id,
                            'status' => 2,
                            'role' => 'user'
                        ]);

                        session()->flash('message', 'Request to join the institute has been sent!');
                        session()->flash('alert-type', 'success');

                        return redirect(route('institutes.index'));
                    }else{
                        session()->flash('message', 'You have already joined the institute!');
                        session()->flash('alert-type', 'danger');
        
                        return redirect(route('institutes.index'));
                    }
            }else{
               
                session()->flash('message', 'Institute is not found!');
                session()->flash('alert-type', 'danger');

                return redirect(route('institutes.index'));
            }   
        }

    }
    public function addStaff(Request $request){

        if($institute = Institute::find($request->id)){
            foreach($institute->staff as $staff){
                if($staff->user_id == Auth::id()){
                    if($staff->status == 1){
                        return view('institutes.show')->with('institute', $institute);
                    }else{
                        session()->flash('message', 'You do not have permission to view staff requests of the institute!');
                        session()->flash('alert-type', 'warning');

                        return redirect(route('institutes.index'));
                    }
                }
            }
            session()->flash('message', 'You do not have permission to view staff requests of the institute!');
            session()->flash('alert-type', 'warning');

            return redirect(route('institutes.index'));

            
        }else{
            session()->flash('message', 'Institute is not available');
            session()->flash('alert-type', 'danger');

            return redirect(route('institutes.index'));
        }

        if($request->add_staff_button == "accept"){
            $staff = Staff::find($request->id);
            $staff->status = 1;
            $staff->save();
            session()->flash('message', 'Staff has beed added into your institute');
            session()->flash('alert-type', 'success');

            return redirect(route('institutes.show', Staff::find($request->id)->institute->id));
        }

        if($request->add_staff_button == "reject"){
            $staff = Staff::find($request->id);
            $staff->status = 0;
            $staff->save();
            session()->flash('message', 'Request has been rejected.');
            session()->flash('alert-type', 'success');

            return redirect(route('institutes.show', Staff::find($request->id)->institute->id));
        }

        
    }
    
}
