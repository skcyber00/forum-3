<?php
namespace App\Controllers;

use App\Models\Membre;
use App\Src\ConnexionClass as Connexion;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class ConnexionController extends Controller {

    /**
     * getConnexion
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function getConnexion(Request $request, Response $response)
    {        
        return $this->render($response, 'sections/connexion.twig');
    }

    /**
     * getDeconnexion
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function getDeconnexion(Request $request, Response $response)
    {        
        unset($_SESSION['username']);
        unset($_SESSION['userid']);
        unset($_SESSION['useremail']);
        // return Routes::redirection();
        return $this->redirect($response, 'connexion');
    }

     /**
     * creatConnexion
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function createConnexion(Request $request, Response $response)
    {
        $errors = [];
        Validator::email()->validate($request->getParam('email')) || $errors['email'] = 'Votre email n\'est pas valide';
        Validator::notEmpty()->validate($request->getParam('password')) || $errors['password'] = 'Veuillez entrer votre mot de passe';
        if (empty($errors)) {

            if(Connexion::CreateSession($request, $response) !== "error"){
                $this->flash('Connexion réussi');
                return $this->redirect($response, 'forum');
            }else{
                $this->flash('Une erreur c\'est produite', 'error');
                $this->flash($errors, 'errors');
                return $this->redirect($response, 'connexion');
            }

        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement', 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'connexion');
        }
    }

}