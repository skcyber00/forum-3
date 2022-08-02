<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SujetResp extends Model{
    protected 	$table		= 'sujet_resp';
    protected 	$primaryKey	= 'id';
    public		$timestamps	= true;

    public function membre()
    {
        return $this->belongsTo('App\Models\Membre');
    }

    public function sujet()
    {
        return $this->belongsTo('App\Models\Sujet');
    }
}