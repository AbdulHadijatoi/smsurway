<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;


class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting=Setting::all()->where('user_id',auth()->user()->id);
        return view('admin/setting',compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'value' => 'required'
        ]);
        $chk=Setting::where('value', $request->value)->where('user_id',auth()->user()->id)->count();
        // dd($chk);
        if($chk==0){
            if(auth()->user()->role=='reseller'){
                $key=$request->key;
            }
            else{
                $key=$request->name;
            }
            $setting=Setting::create([
                'key' => $key,
                'name' => $request->name,
                'value' => $request->value,
                'user_id' => auth()->user()->id,
            ]);
        if($setting){
                return back()->with(
                    'success','New Setting Added Successfully.'
                );
            }
        }
        else{
            return back()->with(
                'error','Setting Value Already Exist.'
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSettingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSettingRequest  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required',
        //     'value' => 'required',
        // ]);
        $status = Setting::find($request->id);
        if($status){
            $status->name = $request->name;
            $status->value = $request->value;
            $status->save();
            return back()->with(
                'success',$request->name.' Setting Updated Successfully.'
            );
        }
        else{
            return back()->with(
                'error',$request->name.' Setting Does Not Update Try Again.'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
