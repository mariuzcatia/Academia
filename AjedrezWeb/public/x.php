<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require _DIR_ . '/../vendor/autoload.php';

session_start();

if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = _DIR_ . $url['path'];
    if (is_file($file)) return false;
}

$loader = new \Twig\Loader\FilesystemLoader(_DIR_ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

// $mongoconn = new \MongoDB\Client("mongodb://localhost");
// $userService = new \Tuiter\Services\UserService($mongoconn->tuiter->users);
// $postService = new \Tuiter\Services\PostService($mongoconn->tuiter->posts);
// $likeService = new \Tuiter\Services\LikeService($mongoconn->tuiter->likes);
// $followService = new \Tuiter\Services\FollowService($mongoconn->tuiter->follows, $userService);
// $loginService = new \Tuiter\Services\LoginService($userService);


$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) use ($twig) {
    
    $template = $twig->load('index.html');
    $response->getBody()->write(
        $template->render([])
    );
    return $response;
});

$app->get('/strategy', function (Request $request, Response $response, array $args) use ($twig) {
    
    $template = $twig->load('tablero.html');
    $response->getBody()->write(
        $template->render([])
    );
    return $response;
});

$app->run();