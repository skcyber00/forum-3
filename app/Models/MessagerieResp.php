<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessagerieResp extends Model{
    protected 	$table		= 'messagerie_resp';
    protected 	$primaryKey	= 'id';
    public		$timestamps	= true;

    public function messagerie()
    {
        return $this->belongsTo('App\Models\Messagerie');
    }
    
    public function membre()
    {
        return $this->belongsTo('App\Models\Membre');
    }
}