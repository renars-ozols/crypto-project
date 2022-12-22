<?php declare(strict_types=1);

session_start();

use App\Controllers\BuySellCryptoController;
use App\Controllers\CryptoController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\MoneyController;
use App\Controllers\RegisterController;
use App\Controllers\ShortSellingController;
use App\Controllers\TransferCryptoController;
use App\Controllers\UserDashboardController;
use App\Redirect;
use App\Repositories\Crypto\CoinMarketCapApiCryptoRepository;
use App\Repositories\Crypto\CryptoRepository;
use App\Repositories\ShortSell\MySQLShortSellRepository;
use App\Repositories\ShortSell\ShortSellRepository;
use App\Repositories\Transactions\MySqlTransactionsRepository;
use App\Repositories\Transactions\TransactionsRepository;
use App\Repositories\UserCrypto\MySqlUserCryptoRepository;
use App\Repositories\UserCrypto\UserCryptoRepository;
use App\Repositories\Users\MySqlUserRepository;
use App\Repositories\Users\UserRepository;
use App\Template;
use App\ViewVariables\AuthViewVariables;
use App\ViewVariables\ErrorsViewVariables;
use App\ViewVariables\InputValueViewVariables;
use App\ViewVariables\ViewVariables;
use Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use function DI\create;

require_once '../vendor/autoload.php';

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

$loader = new FilesystemLoader('../views');
$twig = new Environment($loader);

$container = new DI\Container();
$container->set(
    CryptoRepository::class,
    create(CoinMarketCapApiCryptoRepository::class)
);
$container->set(
    UserRepository::class,
    create(MySqlUserRepository::class)
);
$container->set(
    UserCryptoRepository::class,
    create(MySqlUserCryptoRepository::class)
);
$container->set(
    TransactionsRepository::class,
    create(MySqlTransactionsRepository::class)
);
$container->set(
    ShortSellRepository::class,
    create(MySQLShortSellRepository::class)
);

$viewVariables = [
    AuthViewVariables::class,
    ErrorsViewVariables::class,
    InputValueViewVariables::class
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
    $route->addRoute('POST', '/coin/{id:\d+}/short', [ShortSellingController::class, 'shortCrypto']);
    $route->addRoute('POST', '/coin/{id:\d+}/buy-back', [ShortSellingController::class, 'buyBackCrypto']);
    $route->addRoute('GET', '/coin/{id:\d+}/transfer', [TransferCryptoController::class, 'show']);
    $route->addRoute('POST', '/coin/{id:\d+}/transfer', [TransferCryptoController::class, 'transfer']);
    $route->addRoute('GET', '/search', [CryptoController::class, 'search']);
    $route->addRoute('GET', '/register', [RegisterController::class, 'showForm']);
    $route->addRoute('POST', '/register', [RegisterController::class, 'store']);
    $route->addRoute('GET', '/login', [LoginController::class, 'showForm']);
    $route->addRoute('POST', '/login', [LoginController::class, 'login']);
    $route->addRoute('GET', '/logout', [LogoutController::class, 'logout']);
    $route->addRoute('GET', '/dashboard', [UserDashboardController::class, 'index']);
    $route->addRoute('GET', '/dashboard/short-sell-orders', [UserDashboardController::class, 'showShortSellOrders']);
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