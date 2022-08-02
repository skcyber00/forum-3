<?php
namespace App\Router;

class Routes {

    public function __construct($get){
        $this->redirection();
    }

    public function redirection(){
        if(empty($_GET['url']))
            header('Location: forum');
    }

}