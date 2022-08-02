<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class SessionMiddleware {

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, $next) {
        $this->twig->addGlobal('username', isset($_SESSION['username']) ? $_SESSION['username'] : []);
        $this->twig->addGlobal('useremail', isset($_SESSION['useremail']) ? $_SESSION['useremail'] : []);
        $this->twig->addGlobal('userid', isset($_SESSION['userid']) ? $_SESSION['userid'] : []);
        return $next($request, $response);
    }

}