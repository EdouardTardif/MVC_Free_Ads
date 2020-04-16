<?php

namespace App\Http\Controllers;

use App\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view ('annonce.create');
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
                    echo $currimg;
                    echo '<pre>';
                    var_dump($request->all());
                    echo '</pre>';
                    if($request->hasfile($currimg)){
                        echo 'oui';
                        $file = $request->file($currimg);
                        $extension = $file->getClientOriginalExtension();
                        $filename = $this->random_str() . '.' . $extension;
                        $file->move('uploads/annonce/',$filename);
                        $annonce->$currimg = $filename;
                    }
                }

                $annonce->save();
                return redirect()->back();
                
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
    public function show(Annonce $annonce)
    {
        //
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
    public function update(Request $request, Annonce $annonce)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Annonce  $annonce
     * @return \Illuminate\Http\Response
     */
    public function destroy(Annonce $annonce)
    {
        //
    }
}
