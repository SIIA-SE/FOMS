<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Institute;
use Illuminate\Support\Str;
use App\Http\Requests\Institutes\CreateInstituteRequest;
use App\Http\Requests\Institutes\UpdateInstituteRequest;

class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Return view
        return view('institutes.index')->with('institutes', Institute::all());
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
        
        Institute::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'code' => Str::random(4) . date('Ymd'),
            'image' => $image,

        ]);

        session()->flash('success', 'Institute has been created Successfully!');

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Institute $institute)
    {
        return view('institutes.create')->with('institute', $institute);
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
    public function destroy(Institute $institute)
    {
        $institute->delete();

        session()->flash('success', 'Institute has been deleted Successfully!');

        return redirect(route('institutes.index'));
    }
}
