<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messagerie extends Model{
    protected 	$table		= 'messagerie';
    protected 	$primaryKey	= 'id';
    public		$timestamps	= true;

    public function membre()
    {
        return $this->hasMany('App\Models\Membre');
    }
}