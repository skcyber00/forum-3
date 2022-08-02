<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membre extends Model{
    protected 	$table		= 'membre';
    protected 	$primaryKey	= 'id';
    public		$timestamps	= true;

    public function sujets()
    {
        return $this->hasMany('App\Models\Sujet');
    }

    public function sujetsresp()
    {
        return $this->hasMany('App\Models\SujetResp');
    }
}