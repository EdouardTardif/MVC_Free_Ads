<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    protected $table = 'annonces';
    protected $fillable = [
        'titre', 'description', 'prix', 'type' , 'ville', 'couleur', 'image1','image2','image3','image4','image5'
    ];
    //
}
