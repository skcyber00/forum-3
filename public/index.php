<?php
use App\Controllers\ForumController;
use App\Controllers\ConnexionController;
use App\Controllers\InscriptionController;
use App\Controllers\ContactController;
use App\Controllers\DeconnexionController;
use App\Controllers\ProfilController;
use App\MonExtension;

require '../vendor/autoload.php';

session_start();

$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

require('../app/container.php');

$container = $app->getContainer();



// initialisation connection
$config = parse_ini_file('../app/config/tuto.db.conf.ini');
$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection($config);
$db->setAsGlobal();
$db->bootEloquent();



// Middleware
$app->add(new \App\Middlewares\SessionMiddleware($container->view->getEnvironment()));
$app->add(new \App\Middlewares\FlashMiddleware($container->view->getEnvironment()));
$app->add(new \App\Middlewares\OldMiddleware($container->view->getEnvironment()));
$app->add(new \App\Middlewares\TwigCsrfMiddleware($container->view->getEnvironment(), $container->csrf));
$app->add(new \App\Middlewares\TwigGlobalMiddleward($container->view->getEnvironment()));
$app->add($container->get('csrf'));



// Routes
$app->get('/forum', ForumController::class . ':getForums')->setName('forum');
$app->get('/forum/createsujet', ForumController::class . ':getForumsCreate')->setName('forumcreate');
$app->post('/forum/createsujet', ForumController::class . ':postForumsCreate')->setName('forumcreate');
$app->get('/forum/{categorie}', ForumController::class . ':getForumCategorie')->setName('forumcategorie');
$app->get('/forum/{categorie}/{title}_{id}', ForumController::class . ':getForum')->setName('getforumresp');
$app->post('/forum/{categorie}/{title}_{id}', ForumController::class . ':postForumResponse')->setName('postforumresp');

$app->get('/connexion', ConnexionController::class . ':getConnexion')->setName('connexion');
$app->post('/connexion', ConnexionController::class . ':createConnexion');
$app->get('/deconnexion', ConnexionController::class . ':getDeconnexion')->setName('deconnexion');

$app->get('/inscription', InscriptionController::class . ':getInscription')->setName('inscription');
$app->post('/inscription', InscriptionController::class . ':createInscription');

$app->get('/contact', ContactController::class . ':getContact')->setName('contact');
$app->post('/contact', ContactController::class . ':createContact');

$app->get('/profil', ProfilController::class . ':getDashboard')->setName('profil');
$app->get('/profil/{id}', ProfilController::class . ':getDashboard')->setName('profil');
$app->get('/profil/{id}/profil', ProfilController::class . ':getProfil')->setName('profil');
$app->get('/profil/{id}/sujets', ProfilController::class . ':getSujets')->setName('sujets');
$app->get('/profil/{id}/notifications', ProfilController::class . ':getNotifications')->setName('notifications');
$app->get('/profil/{id}/messageries', ProfilController::class . ':getMessageries')->setName('getmessageries');
$app->get('/profil/{id}/messagerie/{idgroup}', ProfilController::class . ':getMessagerie')->setName('getmessagerie');
$app->post('/profil/{id}/messagerie/{idgroup}', ProfilController::class . ':postMessagerie')->setName('postmessagerie');

$app->run();

$router = new App\Router\Routes($container->view->getEnvironment());