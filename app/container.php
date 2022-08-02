<?php
$container = $app->getContainer();

$container['debug'] = function () {
    return true;
};

$container['csrf'] = function () {
    return new \Slim\Csrf\Guard;
};

// Register component on container
$container['view'] = function ($container) {
    $dir = dirname(__DIR__);
    $twig = new \Slim\Views\Twig($dir . '/app/Views', [
        'debug' => true,
        'cache' => false, $dir . '/tmp/cache'
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $twig->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    $twig->addExtension(new App\MonExtension());
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    // $twig->addExtension(new Twig\Extension\DebugExtension());

    return $twig;
};