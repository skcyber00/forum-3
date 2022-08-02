<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sujet extends Model{
    protected 	$table		= 'sujet';
    protected 	$primaryKey	= 'id';
    public		$timestamps	= true;

    public function membre()
    {
        return $this->belongsTo('App\Models\Membre');
    }

    public function categorie()
    {
        return $this->belongsTo('App\Models\Categorie');
    }

    public function sujetsresp()
    {
        return $this->hasMany('App\Models\SujetResp');
    }

    public function getsujets($query)
    {
        return $query->table('sujet')->select('sujet_text', 'sujet_title', 'id');
    }
}