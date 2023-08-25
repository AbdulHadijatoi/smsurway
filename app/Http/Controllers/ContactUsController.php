<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use App\Http\Requests\StoreContactUsRequest;
use App\Http\Requests\UpdateContactUsRequest;
use Illuminate\Http\Request;
class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('user.contact');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'company' => 'required',
            'contact' => 'required',
            'comment' => 'required',
        ]);
        $status = ContactUs::create([
            'name' => $request->name,
            'email' => $request->email,
            'company' => $request->company,
            'contact' => $request->contact,
            'comment' => $request->comment,
        ]);
        if($status){
            return back()->with(
                'success','Thank you for your feedback.'
            );
        }
        else{
            return back()->with(
                'error','From not submit try again.'
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContactUsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactUsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // dd("HEllo show control");
        // return view('admin.ContactFeeds');
        $feeds= ContactUs::all();
        // ->orderBy('id','desc');
        return view('admin.ContactFeeds',compact('feeds'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function edit(ContactUs $contactUs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContactUsRequest  $request
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactUsRequest $request, ContactUs $contactUs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactUs  $contactUs
     * @return \Illuminate\Http\Response
     */
    public function delFeed(Request $request)
    {
        $feed = ContactUs::find($request->id)->delete();
        if($feed){
            return back()->with('success', "Feed Deleted Sucessfully");
        }
    }
}
