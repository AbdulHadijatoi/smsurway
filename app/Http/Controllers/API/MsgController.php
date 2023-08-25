<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SendMsg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Msg;

class MsgController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = SendMsg::all();
        return response([ 'Msg' => Msg::collection($projects), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SendMsg  $sendMsg
     * @return \Illuminate\Http\Response
     */
    public function show(SendMsg $sendMsg)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SendMsg  $sendMsg
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SendMsg $sendMsg)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SendMsg  $sendMsg
     * @return \Illuminate\Http\Response
     */
    public function destroy(SendMsg $sendMsg)
    {
        //
    }
}
