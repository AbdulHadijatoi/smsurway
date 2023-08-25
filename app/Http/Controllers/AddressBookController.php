<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use App\Http\Requests\StoreAddressBookRequest;
use App\Http\Requests\UpdateAddressBookRequest;
use Illuminate\Http\Request;
class AddressBookController extends Controller
{
    public function __construct()

    {
        $this->middleware('auth');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    // Address Book or Contact Group
    public function address()
    {
        
        $address= AddressBook::where('user_id', auth()->user()->id)->get();
        // dd($address->all());
        return view('user.address',compact('address'));
        // return view('user.address');
    }
    public function addgroup(Request $request)
    {
        $status = AddressBook::create([
            'name' => $request->name,
            'description' => $request->description,
            'numbers' => $request->numbers,
            'user_id' => auth()->user()->id,
        ]);

        if($status){
            return back()->with('success','New Group added sucessfully');
        }else{
            return back()->with('error','Some thing went wrong!');
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
     * @param  \App\Http\Requests\StoreAddressBookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAddressBookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AddressBook  $addressBook
     * @return \Illuminate\Http\Response
     */
    public function show(AddressBook $addressBook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AddressBook  $addressBook
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        // dd("Hello from edit with Req".$request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAddressBookRequest  $request
     * @param  \App\Models\AddressBook  $addressBook
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAddressBookRequest $request, AddressBook $addressBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AddressBook  $addressBook
     * @return \Illuminate\Http\Response
     */
    public function destroy(AddressBook $addressBook)
    {
        //
    }
}
