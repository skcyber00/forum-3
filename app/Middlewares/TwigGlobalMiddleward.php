<?php
namespace App\Middlewares;

use Slim\Http\Request;
use Slim\Http\Response;

class TwigGlobalMiddleward {

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request, Response $response, $next) {
        $get = explode('/', $_GET['url']);
        $this->twig->addGlobal('h1', ucfirst($get[0]));
        return $next($request, $response);
    }

}