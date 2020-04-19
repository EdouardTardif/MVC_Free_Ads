<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller

{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function all(){
        if(Auth::user() && Auth::user()->id){
            $receivers = Message::distinct()
                            ->leftJoin('users', 'messages.id_receiver', '=', 'users.id')
                            ->where('id_sender','=',Auth::user()->id)
                            ->orwhere('id_receiver','=',Auth::user()->id)
                            ->get('id_receiver');
            $contacts = [];
            foreach($receivers as $receiver){
                    $contacts[] = User::find($receiver->id_receiver);
            }
            $receivers = Message::distinct()
                            ->leftJoin('users', 'messages.id_receiver', '=', 'users.id')
                            ->where('id_sender','=',Auth::user()->id)
                            ->orwhere('id_receiver','=',Auth::user()->id)
                            ->get('id_sender');
            foreach($receivers as $receiver){
                    $contacts[] = User::find($receiver->id_sender);
            }
            $messages = null;
            $user = Auth::user();
            echo count($contacts);
            return view ('user.message1',['contacts'=>$contacts,'messages'=>$messages,'user'=>$user]);
        }
    }
    public function message($id,Message $message){
        if(Auth::user() && Auth::user()->id){
            $message->id_sender = Auth::user()->id;
            $message->id_receiver = $id;
            $message->content = '';
            $message->is_seen = 0;
            $message->save();
            return redirect('/message');
        } else {
            return redirect()->back();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send($id,Request $request, Message $message)
    {
        if(Auth::user() && Auth::user()->id){
            $message->id_sender = Auth::user()->id;
            $message->id_receiver = $id;
            $message->content = $request['message'];
            $message->is_seen = 0;
            $message->save();
            return redirect("/message/$id");
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Auth::user() && Auth::user()->id){
            $receivers = Message::distinct()
                            ->leftJoin('users', 'messages.id_receiver', '=', 'users.id')
                            ->where('id_sender','=',Auth::user()->id)
                            ->orwhere('id_receiver','=',Auth::user()->id)
                            ->get('id_receiver');
            $contacts = [];
            foreach($receivers as $receiver){
                    $contacts[] = User::find($receiver->id_receiver);
            }
            $receivers = Message::distinct()
                            ->leftJoin('users', 'messages.id_receiver', '=', 'users.id')
                            ->where('id_sender','=',Auth::user()->id)
                            ->orwhere('id_receiver','=',Auth::user()->id)
                            ->get('id_sender');
            foreach($receivers as $receiver){
                    $contacts[] = User::find($receiver->id_sender);
            }
            $messages = Message::where('id_sender','=',Auth::user()->id)
                                ->where('id_receiver','=',$id)
                                ->orwhere('id_receiver','=',Auth::user()->id)
                                ->where('id_sender','=',$id)
                                ->get();

            foreach($messages as $message){
                $message->is_seen = 1;
                $message->save();
            }
            
            $user = Auth::user();
            return view ('user.message2',['contacts'=>$contacts,'messages'=>$messages,'user'=>$user,'contactid'=>$id]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
