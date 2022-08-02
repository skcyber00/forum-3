<?php
namespace App\Src;

use App\Models\Membre;
use App\Controllers\Controller;
use Respect\Validation\Validator;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ConnexionClass extends Controller {
    
    public function CreateSession(Request $request, Response $response){
        $membre = Membre::select('id', 'memb_nom', 'memb_prenom')->where('memb_email', $request->getParam('email'))->where('memb_password', $request->getParam('password'))->get();     
        foreach ($membre as $membres){
            $membreid = $membres['id'];
            $membrenom = $membres['memb_nom'];
            $membreprenom = $membres['memb_prenom'];
        }

        if(!empty($membres)){
            $_SESSION['username'] = $membrenom . ' ' . $membreprenom;
            $_SESSION['userid'] = $membreid;
            $_SESSION['useremail'] = $request->getParam('email');
        } else {
            return $erreur = "error";
        }
    }

}