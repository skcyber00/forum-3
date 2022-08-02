<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class ContactController extends Controller {

    /**
     * getContact
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function getContact(Request $request, Response $response)
    {        
        return $this->render($response, 'sections/contact.twig');
    }

     /**
     * creatContact
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function createContact(Request $request, Response $response)
    {
        $errors = [];
        Validator::email()->validate($request->getParam('email')) || $errors['email'] = 'Votre email n\'est pas valide';
        Validator::notEmpty()->validate($request->getParam('name')) || $errors['name'] = 'Veuillez entrer votre nom';
        Validator::notEmpty()->validate($request->getParam('content')) || $errors['content'] = 'Veuillez entrer votre nom';
        if (empty($errors)) {
            $this->flash('Votre message a bien été envoyé');
            return $this->redirect($response, 'contact');
        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement', 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'contact', 400);
        }
    }

}