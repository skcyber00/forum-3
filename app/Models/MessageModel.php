<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model{
    protected 	$table		= 'message';
    protected 	$primaryKey	= 'id';
    public		$timestamps	= true;
    
    public function membre()
    {
        // return $this->belongsTo('App\Models\Membre');
        // return $this->belongsToMany('App\Models\Membre', 'message', 'from_membre_id');
    }
}

/*
users
    id - integer
    name - string

roles
    id - integer
    name - string

role_user
    user_id - integer
    role_id - integer
    */