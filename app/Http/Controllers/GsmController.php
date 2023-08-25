<?php

namespace App\Http\Controllers;

use App\Models\GsmNetwork;
use App\Models\GsmPrefix;
use Illuminate\Http\Request;
class GsmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gsmNetworks(){
        $gsm= GsmNetwork::all()->where('user_id',auth()->user()->id);
        return view('admin.gsm',compact('gsm'));
    }
    public function addGsmNetwork(Request $request)
    {
        $this->validate($request, [
            'network_name' => 'required',
            'network_price' => 'required',
        ]);
        $status = GsmNetwork::create([
            'network_name' => $request->network_name,
            'network_price' => $request->network_price,
            'user_id' => auth()->user()->id,
        ]);
        if($status){
            return back()->with(
                'success','GSM Network Added Successfully.'
            );
        }
        else{
            return back()->with(
                'error','GSM Network Not Add Try Again.'
            );
        }
    }
    public function updateGsmNetwork(Request $request)
    {
        $this->validate($request, [
            'network_id' => 'required',
            'network_name' => 'required',
            'network_price' => 'required',
        ]);
        $status = GsmNetwork::find($request->network_id);
        if($status){
            $status->network_name = $request->network_name;
            $status->network_price = $request->network_price;
            $status->save();
            return back()->with(
                'success','GSM Network Updated Successfully.'
            );
        }
        else{
            return back()->with(
                'error','GSM Network Does Not Update Try Again.'
            );
        }
    }
    
    // Code For Network Prefixes
    
    public function gsmPrefix(){
        $prefix= GsmPrefix::all();
        return view('admin.prefix',compact('prefix'));
    }

    public function addPrefix(Request $request)
    {
        $this->validate($request, [
            'network_name' => 'required',
            'network_prefix' => 'required',
        ]);
        $status = GsmPrefix::create([
            'network_name' => $request->network_name,
            'network_prefix' => $request->network_prefix,
        ]);
        if($status){
            return back()->with(
                'success','GSM Network Prefix Added Successfully.'
            );
        }
        else{
            return back()->with(
                'error','GSM Network Prefix Not Add Try Again.'
            );
        }
    }

    public function delPrefix(Request $request)
    {
        $res = GsmPrefix::find($request->id)->delete();
        if($res){
            return back()->with('success', "Prefix Deleted Sucessfully.");
        }
        else{
            return back()->with('error', "Prefix Not Delete Try Again."); 
        }
    }
    public function delGsm(Request $request)
    {
        $res = GsmNetwork::find($request->id)->delete();
        if($res){
            return back()->with('success', "GSM Network Deleted Sucessfully.");
        }
        else{
            return back()->with('error', "GSM Network Not Delete Try Again."); 
        }
        
    }
    public function updatePrefix(Request $request)
    {
        $this->validate($request, [
            'prefix_id' => 'required',
            'network_name' => 'required',
            'network_prefix' => 'required',
        ]);
        $status = GsmPrefix::find($request->prefix_id);
        if($status){
            $status->network_name = $request->network_name;
            $status->network_prefix = $request->network_prefix;
            $status->save();
            return back()->with(
                'success','GSM Network Prefix Updated Successfully.'
            );
        }
        else{
            return back()->with(
                'error','GSM Network Prefix Does Not Update Try Again.'
            );
        }
    }
}