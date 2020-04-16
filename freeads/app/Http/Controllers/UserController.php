<?php

namespace App\Http\Controllers;


use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
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
    public function store(Request $request)
    {
        //
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
    public function edit()
    {
        if(Auth::user()){
            $user = User::find(Auth::user()->id);

            if($user){
                return view ('user.edit')->withUser($user);
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if($user){
            $validate = null;
            if(Auth::user()->email === $request['email']){
                $validate = $request->validate([
                    'name' => 'required|min:2|max:255',
                    'email' => 'required|email'
                ]);
            } else {
                $validate = $request->validate([
                    'name' => 'required|min:2|max:255',
                    'email' => 'required|email|unique:users'
                ]);
            }
            
            if($validate){
                $user->name = $request['name'];
                $user->email = $request['email'];
    
                $user->save();
                $request->session()->flash('success','Informations MAJ');
            }
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function block(Request $request){
        $user = User::find(Auth::user()->id);
        if($user){
                // $date = new \DateTime();
                // $timestamp = $date->getTimestamp();
                $today = date("Y-m-d H:i:s");  
                $user->blocked_at = $today;
                $user->save();
                $request->session()->flash('success','Account suspended');
                Auth::logout();
                return redirect('/login');
        } else {
            return redirect()->back();
        }
    }

    public function destroy()
    {
        if(Auth::user()){
            $user = User::find(Auth::user()->id);

            if($user){
                return view ('user.edit')->withUser($user);
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }
}
