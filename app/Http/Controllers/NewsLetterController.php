<?php

namespace App\Http\Controllers;

use App\Mail\NewsLetterEmail;
use App\Models\NewsLetter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsLetterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.newsletter');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNewsLetterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        // dd($request->all());
        if($request->contact=='all'){
            $emails = User::where("role","user")->get('email');
            // foreach ($users as $key => $user) {
            //     $keyword=NewsLetter::create([
            //         'to' => $user->email,
            //         'from' => auth()->user()->email,
            //         'subject' => $request->subject,
            //         'msg' => $request->msg,
            //         'user_id' => auth()->user()->id,
            //     ]);
            // }
        }
        else{
            $to = Str::squish($request->to);
            $emails = explode(',', $to);
        }
            $uuid = Str::uuid()->toString();
            foreach ($emails as $email) {
                $keyword=NewsLetter::create([
                    'to' => $email,
                    'from' => auth()->user()->email,
                    'subject' => $request->subject,
                    'msg' => $request->msg,
                    'user_id' => auth()->user()->id,
                    'uuid' => $uuid,
                ]);
                $news=NewsLetter::get()->where('uuid',$uuid)->reverse()->first();
                Mail::to($email)->send(new NewsLetterEmail($email,$news));
            }
        // }
        
        if($keyword){
            return back()->with(
                'success','NewsLetter Sent Successfully.'
            );
        }else{
            return back()->with(
                'error','Unexpacted Error'
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNewsLetterRequest  $request
     * @param  \App\Models\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NewsLetter  $newsLetter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }
}
