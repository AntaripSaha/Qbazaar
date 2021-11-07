<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Validator;

class UnitController extends Controller
{
	
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        date_default_timezone_set(get_option('timezone','Asia/Dhaka'));
    }
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = Unit::all()->sortByDesc("id");
        return view('backend.unit.list',compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if( ! $request->ajax()){
            return back();
        }else{
           return view('backend.unit.modal.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	
        $validator = Validator::make($request->all(), [
            'trans.name' => 'required',
            'trans.short_name' => 'required',
        ]);

        if ($validator->fails()) {
            if($request->ajax()){ 
                return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
            }else{
                return redirect()->route('units.create')
                	             ->withErrors($validator)
                	             ->withInput();
            }			
        } 

        $unit = new Unit();
        $unit->save();
        $unit->name = $unit->translation->name;
        $unit->short_name = $unit->translation->short_name;

        if(! $request->ajax()){
           return redirect()->route('units.create')->with('success', _lang('Saved Successfully'));
        }else{
           return response()->json(['result'=>'success','action'=>'store','message'=>_lang('Saved Successfully'),'data'=>$unit, 'table' => '#units_table']);
        }
        
   }
	

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $unit = Unit::find($id);
        if(! $request->ajax()){
            return back();
        }else{
            return view('backend.unit.modal.view',compact('unit','id'));
        } 
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        $unit = Unit::find($id);
        if(! $request->ajax()){
            return view('backend.unit.edit',compact('unit','id'));
        }else{
            return view('backend.unit.modal.edit',compact('unit','id'));
        }  
        
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
		$validator = Validator::make($request->all(), [
            'trans.name' => 'required',
            'trans.short_name' => 'required',
        ]);

		if ($validator->fails()) {
			if($request->ajax()){ 
				return response()->json(['result'=>'error','message'=>$validator->errors()->all()]);
			}else{
				return redirect()->route('units.edit', $id)
							->withErrors($validator)
							->withInput();
			}			
		}    	
		
        $unit = Unit::find($id);
        $unit->save();
        $unit->name = $unit->translation->name;
        $unit->short_name = $unit->translation->short_name;
		
		if(! $request->ajax()){
           return redirect()->route('units.index')->with('success', _lang('Updated Successfully'));
        }else{
		   return response()->json(['result'=>'success','action'=>'update', 'message'=>_lang('Updated Successfully'),'data'=>$unit, 'table' => '#units_table']);
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
        $unit = Unit::find($id);
        $unit->delete();
        return redirect()->route('units.index')->with('success',_lang('Deleted Successfully'));
    }
}