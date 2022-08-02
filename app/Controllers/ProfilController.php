<?php
namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Sujet;
use App\Models\Membre;
use App\Models\Messagerie;
use App\Models\MessagerieResp;
use App\Models\Message;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class ProfilController extends Controller {

    public $menu = [false];

    /**
     * getProfil
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getProfil(Request $request, Response $response, array $args)
    {
        $t = [];
        if((int)$_SESSION['userid'] === (int)$args['id'])
            $t = $this->menu;

        $membre = Membre::where('id', $args['id'])->get();
        $sujets = Sujet::with('membre')->where('membre_id', $args['id'])->orderBy('id', 'desc')->get();
        $mess_id = MessagerieResp::select('mess_id')->where('membre_id', $args['id'])->orWhere('membre_id', $_SESSION['userid'])->limit(1)->get();

        return $this->render($response, 'sections/profil-profil.twig', ['menubarre' => $t, 'sujets' => $sujets, 'membre' => $membre, 'idgroup' => $mess_id]);
    }

    /**
     * getDashboard
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getDashboard(Request $request, Response $response, array $args)
    {
        $t = [];
        if((int)$_SESSION['userid'] === (int)$args['id'])
            $t = $this->menu;

        $membre = Membre::where('id', $args['id'])->get();
        $sujets = Sujet::with('membre')->where('membre_id', $args['id'])->orderBy('id', 'desc')->get();
        $mess_id = MessagerieResp::select('mess_id')->where('membre_id', $args['id'])->orWhere('membre_id', $_SESSION['userid'])->limit(1)->get();
    
        return $this->render($response, 'sections/profil.twig', ['menubarre' => $t, 'sujets' => $sujets, 'membre' => $membre, 'idgroup' => $mess_id]);
    }

    /**
     * getSujets
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getSujets(Request $request, Response $response, array $args)
    {
        $t = [];
        if((int)$_SESSION['userid'] === (int)$args['id'])
            $t = $this->menu;

        $membre = Membre::where('id', $args['id'])->get();
        $sujets = Sujet::with('membre')->where('membre_id', $args['id'])->orderBy('id', 'desc')->get();
        $mess_id = MessagerieResp::select('mess_id')->where('membre_id', $args['id'])->orWhere('membre_id', $_SESSION['userid'])->limit(1)->get();

        return $this->render($response, 'sections/profil-messujets.twig', ['menubarre' => $t, 'sujets' => $sujets, 'membre' => $membre, 'idgroup' => $mess_id]);
    }

    /**
     * getNotifications
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getNotifications(Request $request, Response $response, array $args)
    {
        $t = [];
        if((int)$_SESSION['userid'] === (int)$args['id'])
            $t = $this->menu;

        $membre = Membre::where('id', $args['id'])->get();
        $mess_id = MessagerieResp::select('mess_id')->where('membre_id', $args['id'])->orWhere('membre_id', $_SESSION['userid'])->limit(1)->get();

        return $this->render($response, 'sections/profil-notifications.twig', ['menubarre' => $t, 'membre' => $membre, 'idgroup' => $mess_id]);
    }

    /**
     * getMessageries
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getMessageries(Request $request, Response $response, array $args)
    {
        $t = [];
        if((int)$_SESSION['userid'] === (int)$args['id'])
            $t = $this->menu;

        $membre = Membre::where('id', $args['id'])->get();
        $mess_id = MessagerieResp::select('mess_id')->where('membre_id', $args['id'])->orWhere('membre_id', $_SESSION['userid'])->limit(1)->get();

        return $this->render($response, 'sections/profil-messageries.twig', ['menubarre' => $t, 'membre' => $membre, 'idgroup' => $mess_id]);
    }
    
    /**
     * getMessagerie
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getMessagerie(Request $request, Response $response, array $args)
    {
        $t = [];
        if((int)$_SESSION['userid'] === (int)$args['id'])
            $t = $this->menu;

        $membre = Membre::where('id', $args['id'])->get();
        $messagerieresp = MessagerieResp::with('membre', 'messagerie')->where('mess_id', $args['idgroup'])->get();

        return $this->render($response, 'sections/profil-messagerie-resp.twig', ['menubarre' => $t, 'membre' => $membre, 'messagerieresp' => $messagerieresp, 'idgroup' => $args['idgroup']]);
    }

    /**
     * postMessagerie
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function postMessagerie(Request $request, Response $response, array $args)
    {
        Validator::notEmpty()->validate($request->getParam('content')) || $errors['content'] = 'Veuillez entrer votre réponse';
        if (empty($errors)) {

            $messagerieresp = MessagerieResp::where('mess_id', $args['idgroup'])->orWhere('membre_id', $_SESSION['userid'])->get();
            if($messagerieresp){
                $message = new MessagerieResp();
                $message->membre_id = $_SESSION['userid'];
                $message->mess_content = $request->getParam('content');
                $message->mess_id = $args['idgroup'];
                $message->save();
            }else{
                $messagerie = new Messagerie();
                $messagerie->membre_id = $args['id'];
                $messagerie->save();
                $id = $messagerie->id;
        
                $message = new MessagerieResp();
                $message->membre_id = $_SESSION['userid'];
                $message->mess_content = $request->getParam('content');
                $message->mess_id = $id;
                $message->save();
            }
    
            $this->flash('Création de votre sujet réussi');

            return $this->redirect($response, 'getmessagerie', ['id' => $args['id'], 'iduser' => $_SESSION['userid'], 'idgroup' => $args['idgroup']]);

        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement', 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'getmessagerie', ['id' => $args['id'], 'iduser' => $_SESSION['userid'], 'idgroup' => $args['idgroup']]);
        }
    }

}