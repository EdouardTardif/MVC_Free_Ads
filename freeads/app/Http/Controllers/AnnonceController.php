<?php

namespace App\Http\Controllers;

use App\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $annonces = Annonce::all();
        // var_dump($annonces);
        return view ('annonce.listall',compact('annonces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()){
            return view ('annonce.create');
        } else {
            return redirect()->back();
        }
        
    }
    public function userannonces(Annonce $annonce){
        if(Auth::user()->id){
            $annonces = Annonce::where('id_user', Auth::user()->id)->get();
            // $annonces = DB::table('annonces')->where('id_user', Auth::user()->id);
            return view ('annonce.mesannonces',compact('annonces'));

        } else {
            return redirect()->back();
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
        if(Auth::user() && Auth::user()->id){
            $annonce = new Annonce();

            $validate = $request->validate([
                'titre' => 'required|min:2|max:255',
                'description' => 'required|min:10',
                'prix' => 'required|int',
                'type' => 'required|max:255',
                'ville' => 'required|max:255',
                'prix' => 'required|int'
            ]);
            if($validate){    
                $annonce->id_user = Auth::user()->id;
                $annonce->titre = $request['titre'];
                $annonce->description = $request['description'];
                $annonce->prix = $request['prix'];
                $annonce->type = $request['type'];
                $annonce->ville = $request['ville'];
                if(isset($request['couleur'])){
                    $annonce->couleur = $request['couleur'];
                }

                for($i = 1; $i <= 5; $i++){
                    $currimg = "image$i";
                    if($request->hasfile($currimg)){
                        $file = $request->file($currimg);
                        $extension = $file->getClientOriginalExtension();
                        $filename = $this->random_str() . '.' . $extension;
                        $file->move('uploads/annonce/',$filename);
                        $annonce->$currimg = $filename;
                    }
                }

                $annonce->save();
                return redirect('/user/annonces');
                
            }
        }
    }
    public function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */

    public function info($id){
        if(Auth::user() && Auth::user()->id) {
            $annonce = Annonce::find($id);

            if($annonce && Auth::user()->id === $annonce->id_user){
                return view('annonce.info',compact('annonce'));
            } else {
                return view('annonce.info',compact('annonce'));
            }
        } else {
            return redirect()->back();
        }
    }


    public function show($id)
    {
        if(Auth::user() && Auth::user()->id) {
            $annonce = Annonce::find($id);

            if($annonce && Auth::user()->id === $annonce->id_user){
                return view('annonce.edit',compact('annonce'));
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function edit(Annonce $annonce)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        if(Auth::user() && Auth::user()->id) {
            $annonce = Annonce::find($id);

            if($annonce && Auth::user()->id === $annonce->id_user){
                $validate = $request->validate([
                    'titre' => 'required|min:2|max:255',
                    'description' => 'required|min:10',
                    'prix' => 'required|int',
                    'type' => 'required|max:255',
                    'ville' => 'required|max:255',
                    'prix' => 'required|int'
                ]);
                
                if($validate){
                    $annonce->titre = $request['titre'];
                    $annonce->description = $request['description'];
                    $annonce->prix = $request['prix'];
                    $annonce->type = $request['type'];
                    $annonce->ville = $request['ville'];
                    if(isset($request['couleur'])){
                        $annonce->couleur = $request['couleur'];
                    }
        
                    $annonce->save();
                    $request->session()->flash('success','Annonce Mise a jour');
                }
                return redirect()->back();
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Auth::user()->id) {
            $annonce = Annonce::find($id);
            if($annonce && Auth::user()->id === $annonce->id_user){
                $annonce->delete();
                return redirect('/user/annonces');
            } else {
                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }



    public function search(Request $request){
        $validate = $request->validate([
            'titre' => 'nullable|max:255',
            'prix' => 'nullable|int',
            'type' => 'nullable|max:255',
            'ville' => 'nullable|max:255',
            'list' => 'required'
        ]);
        $this->titre = $request['titre'] ?? '';
        $this->prix = $request['prix'] ?? 100000000000;
        $this->type = $request['type'] ?? '';
        $this->ville = $request['ville'] ?? '';
        if('prixc' === $request['list']){
            $orderbycol = 'prix';
            $orderbyval = 'ASC';
        } elseif('prixd' === $request['list']) {
            $orderbycol = 'prix';
            $orderbyval = 'DESC';
        } elseif('date' === $request['list']) {
            $orderbycol = 'created_at';
            $orderbyval = 'ASC';
        } else {
            $orderbycol = 'prix';
            $orderbyval = 'ASC';
        }
        $annonces = Annonce::where('titre', 'LIKE', "%".$this->titre."%")
                            ->where('prix', '<=' , $this->prix)
                            ->where('ville', 'LIKE',"%".$this->ville."%")
                            ->where('type', 'LIKE',"%".$this->type."%")
                            ->orderBy($orderbycol, $orderbyval)
                            ->get();
        if($annonces){
            return view ('annonce.listall',compact('annonces'));
        }
    }
}
