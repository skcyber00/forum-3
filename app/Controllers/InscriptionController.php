<?php
namespace App\Controllers;

use App\Models\Membre;
use App\Src\ConnexionClass as Connexion;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class InscriptionController extends Controller {

    /**
     * getInscription
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function getInscription(Request $request, Response $response)
    {        
        return $this->render($response, 'sections/inscription.twig');
    }

     /**
     * creatInscription
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function createInscription(Request $request, Response $response)
    {
        $errors = [];
        Validator::email()->validate($request->getParam('email')) || $errors['email'] = 'Votre email n\'est pas valide';
        Validator::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez entrer votre nom';
        Validator::notEmpty()->validate($request->getParam('firstname')) || $errors['firstname'] = 'Veuillez entrer votre prénom';
        Validator::notEmpty()->validate($request->getParam('password')) || $errors['password'] = 'Veuillez entrer votre mot de passe';
        if (empty($errors)) {
            $inscription = new Membre();
            $inscription->memb_email = $request->getParam('email');
            $inscription->memb_nom = $request->getParam('name');
            $inscription->memb_prenom = $request->getParam('firstname');
            $inscription->memb_password = $request->getParam('password');
            $inscription->save();

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
            return $this->redirect($response, 'inscription');
        }
    }

}