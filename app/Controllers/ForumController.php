<?php
namespace App\Controllers;

use App\Models\Categorie;
use App\Models\Sujet;
use App\Models\SujetResp;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Validator;

class ForumController extends Controller {

    /**
     * getForums
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getForums(Request $request, Response $response, array $args)
    {
        $categories = Categorie::all();
        $sujets = Sujet::with('membre', 'categorie')->orderBy('id', 'desc')->get(); //->makeVisible(['memb_password']); => pour le rendre visible
        
        return $this->render($response, 'sections/forum.twig', ['menubarre', 'categories' => $categories, 'sujets' => $sujets]);
    }


    /**
     * getForum
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getForum(Request $request, Response $response, array $args)
    {
        $categories = Categorie::all();
        $sujets = Sujet::with('membre', 'categorie')->where('id', $args['id'])->orderBy('id', 'desc')->get();
        $sujetsresp = SujetResp::with('sujet')->where('sujet_id', $args['id'])->orderBy('id', 'asc')->get();

        return $this->render($response, 'sections/forum-detail.twig', ['menubarre', 'categories' => $categories, 'sujets' => $sujets, 'sujetsresp' => $sujetsresp, 'title' => $args['title'] ]);
    }


    /**
     * getForumsCreate
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getForumsCreate(Request $request, Response $response, array $args)
    {
        $categories = Categorie::all();
        $sujets = Sujet::with('membre')->orderBy('id', 'desc')->get(); //->makeVisible(['memb_password']); => pour le rendre visible
        
        return $this->render($response, 'sections/forum-create-sujet.twig', ['menubarre', 'categories' => $categories, 'sujets' => $sujets]);
    }


    /**
     * postForumsCreate
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function postForumsCreate(Request $request, Response $response, array $args)
    {
        $errors = [];
        Validator::notEmpty()->validate($request->getParam('title')) || $errors['title'] = 'Veuillez entrer votre réponse';
        Validator::notEmpty()->validate($request->getParam('content')) || $errors['content'] = 'Veuillez entrer votre réponse';
        Validator::notEmpty()->validate($request->getParam('userid')) || $errors['userid'] = 'Veuillez entrer votre réponse';
        Validator::notEmpty()->validate($request->getParam('categorie')) || $errors['categorie'] = 'Veuillez entrer votre réponse';
        if (empty($errors)) {

            $categories = Categorie::all();
            $categorie = Categorie::select('id')->where('cate_label', $request->getParam('categorie'))->get();
            foreach ($categorie as $categorieid){
                $categorieid = $categorieid['id'];
            }
            
            $sujetcreate = new Sujet();
            $sujetcreate->membre_id = $request->getParam('userid');
            $sujetcreate->sujet_title = $request->getParam('title');
            $sujetcreate->sujet_text = $request->getParam('content');
            $sujetcreate->categorie_id = $categorieid;
            $sujetcreate->save();
            $this->flash('Création de votre sujet réussi');

            $id = $sujetcreate->id;

            return $this->redirect($response, 'getforumresp', ['categorie' => $request->getParam('categorie'), 'title' => $request->getParam('title'), 'id' => $id]);
        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement', 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'forum', ['categorie' => $request->getParam('categorie'), 'title' => $request->getParam('title'), 'id' => $id]);
        }
    }


    /**
     * getForumCategorie
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function getForumCategorie(Request $request, Response $response, array $args)
    {
        $categories = Categorie::all();
        $categorie = Categorie::select('id')->where('cate_label', $args['categorie'])->get();
        foreach ($categorie as $categorieid){
            $categorieid = $categorieid['id'];
        }
        $sujets = Sujet::with('membre')->where('categorie_id', $categorieid)->orderBy('id', 'desc')->get(); //->makeVisible(['memb_password']); => pour le rendre visible
        
        return $this->render($response, 'sections/forum.twig', ['menubarre', 'categories' => $categories, 'sujets' => $sujets]);
    }


    /**
     * postForumResponse
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return void
     */
    public function postForumResponse(Request $request, Response $response, array $args)
    {
        $errors = [];
        Validator::notEmpty()->validate($request->getParam('content')) || $errors['content'] = 'Veuillez entrer votre réponse';
        Validator::notEmpty()->validate($request->getParam('userid')) || $errors['userid'] = 'Veuillez entrer votre réponse';
        if (empty($errors)) {
            
            $sujetreponse = new SujetResp();
            $sujetreponse->membre_id = $request->getParam('userid');
            $sujetreponse->sujet_id = $args['id'];
            $sujetreponse->resp_content = $request->getParam('content');
            $sujetreponse->save();
            $this->flash('Envoie de votre réponse réussi');

            return $this->redirect($response, 'getforumresp', ['categorie' => $args['categorie'], 'title' => $args['title'], 'id' => $args['id']]);
        } else {
            $this->flash('Certains champs n\'ont pas été rempli correctement', 'error');
            $this->flash($errors, 'errors');
            return $this->redirect($response, 'getforumresp', ['categorie' => $args['categorie'], 'title' => $args['title'], 'id' => $args['id']]);
        }
    }

}