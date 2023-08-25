<?php

namespace App\Http\Controllers;

use App\Models\ManageKeyword;
use Illuminate\Http\Request;

class ManagekeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword=ManageKeyword::all();
        return view('admin/managekeyword',compact('keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'keyword' => 'required',
        ]);
        $chk=ManageKeyword::where('keyword', $request->keyword )->exists();
        if($chk==null){
            $keyword=ManageKeyword::create([
                'keyword' => $request->keyword,
                'user_id' => auth()->user()->id,
            ]);
        if($keyword){
                return back()->with(
                    'success','Keyword Added Successfully.'
                );
            }
        }
        else{
            return back()->with(
                'error','Keyword Already Exist.'
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoremanagekeywordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\managekeyword  $managekeyword
     * @return \Illuminate\Http\Response
     */
    public function show(managekeyword $managekeyword)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\managekeyword  $managekeyword
     * @return \Illuminate\Http\Response
     */
    public function edit(managekeyword $managekeyword)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatemanagekeywordRequest  $request
     * @param  \App\Models\managekeyword  $managekeyword
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\managekeyword  $managekeyword
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // return $request->all();
        $res=ManageKeyword::find($request->id)->delete();
        if($res){
            return back()->with('success', "Keyword Deleted Sucessfully.");
        }
        else{
            return back()->with('error', "Keyword Not Delete Try Again.");
        }
    }
}
