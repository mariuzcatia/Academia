<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

session_start();

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) return false;
}

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

$mongoconn = new \MongoDB\Client("mongodb://localhost");
$userService = new \Tuiter\Services\UserService(array($mongoconn->tuiter->users));
$postService = new \Tuiter\Services\PostService($mongoconn->tuiter->posts);
$likeService = new \Tuiter\Services\LikeService($mongoconn->tuiter->likes);
$followService = new \Tuiter\Services\FollowService($mongoconn->tuiter->follows, $userService);
$loginService = new \Tuiter\Services\LoginService($userService);


$app = AppFactory::create();

$app->add(function($serverRequest, $requestHandler)
            use ($twig, $loginService, $userService,
            $postService, $likeService, $followService) {

    $user = $loginService->getLoggedUser();
    $serverRequest = $serverRequest->withAttribute("user", $user);
    
    $serverRequest = $serverRequest->withAttribute("twig", $twig);
    $serverRequest = $serverRequest->withAttribute("userService", $userService);
    $serverRequest = $serverRequest->withAttribute("loginService", $loginService);
    $serverRequest = $serverRequest->withAttribute("followService", $followService);
    $serverRequest = $serverRequest->withAttribute("likeService", $likeService);
    $serverRequest = $serverRequest->withAttribute("postService", $postService);
    $serverRequest = $serverRequest->withAttribute('login', !$user instanceof \Tuiter\Models\UserNull);

    return $requestHandler->handle($serverRequest);
});

$controllerService = new \Tuiter\Services\ControllerService();
$controllerService->setup($app, __DIR__ . '/../src/Controllers/' );

$app->run();