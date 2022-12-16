<?php declare(strict_types=1);

session_start();

use App\Controllers\BuySellCryptoController;
use App\Controllers\CryptoController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\MoneyController;
use App\Controllers\RegisterController;
use App\Controllers\UserDashboardController;
use App\Redirect;
use App\Template;
use App\ViewVariables\AuthViewVariables;
use App\ViewVariables\ErrorsViewVariables;
use App\ViewVariables\ViewVariables;
use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

$loader = new FilesystemLoader('../views');
$twig = new Environment($loader);
//TODO: cleanup container
$container = new DI\Container();
$container->set(
    \App\Repositories\Crypto\CryptoRepository::class,
    \DI\create(\App\Repositories\Crypto\CoinMarketCapApiCryptoRepository::class)
);
$container->set(
    \App\Repositories\Users\UserRepository::class,
    \DI\create(\App\Repositories\Users\MySqlUserRepository::class)
);
$container->set(
    \App\Repositories\UserCrypto\UserCryptoRepository::class,
    \DI\create(\App\Repositories\UserCrypto\MySqlUserCryptoRepository::class)
);
$container->set(
    \App\Repositories\Transactions\TransactionsRepository::class,
    \DI\create(\App\Repositories\Transactions\MySqlTransactionsRepository::class)
);

//TODO: Can implement auto read from directory
$viewVariables = [
    AuthViewVariables::class,
    ErrorsViewVariables::class
];

foreach ($viewVariables as $variable) {
    /** @var ViewVariables $variable */
    $variable = new $variable;
    $twig->addGlobal($variable->getName(), $variable->getValue());
}

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $route) {
    $route->addRoute('GET', '/', [CryptoController::class, 'index']);
    $route->addRoute('GET', '/coin/{id:\d+}', [CryptoController::class, 'show']);
    $route->addRoute('POST', '/coin/{id:\d+}/buy', [BuySellCryptoController::class, 'buyCrypto']);
    $route->addRoute('POST', '/coin/{id:\d+}/sell', [BuySellCryptoController::class, 'sellCrypto']);
    $route->addRoute('GET', '/search', [CryptoController::class, 'search']);
    $route->addRoute('GET', '/register', [RegisterController::class, 'showForm']);
    $route->addRoute('POST', '/register', [RegisterController::class, 'store']);
    $route->addRoute('GET', '/login', [LoginController::class, 'showForm']);
    $route->addRoute('POST', '/login', [LoginController::class, 'login']);
    $route->addRoute('GET', '/logout', [LogoutController::class, 'logout']);
    $route->addRoute('GET', '/dashboard', [UserDashboardController::class, 'index']);
    $route->addRoute('POST', '/deposit', [MoneyController::class, 'deposit']);
    $route->addRoute('POST', '/withdraw', [MoneyController::class, 'withdraw']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controller, $method] = $handler;

        $response = $container->get($controller)->{$method}($vars);

        if ($response instanceof Template) {
            echo $twig->render($response->getPath(), $response->getParams());
            unset($_SESSION['errors']);
        }

        if ($response instanceof Redirect) {
            header('Location: ' . $response->getUrl());
        }
        break;
}