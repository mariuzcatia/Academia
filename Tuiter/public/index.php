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
$userService = new \Tuiter\Services\UserService($mongoconn->tuiter->users);
$postService = new \Tuiter\Services\PostService($mongoconn->tuiter->posts);
$likeService = new \Tuiter\Services\LikeService($mongoconn->tuiter->likes);
$followService = new \Tuiter\Services\FollowService($mongoconn->tuiter->follows, $userService);
$loginService = new \Tuiter\Services\LoginService($userService);


$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) use ($twig) {
    
    $template = $twig->load('index.html');

    $response->getBody()->write(
        $template->render([])
    );
    return $response;
});

$app->post('/login', function (Request $request, Response $response, array $args) use ($twig, $loginService) {
    $userId = $_POST['userId'];
    $password = $_POST['password'];
    
    $user = $loginService->login($userId, $password);
    
    if($user instanceof \Tuiter\Models\User){
        $response = $response->withStatus(302);
        $response = $response->withHeader("Location", "/feed");
        return $response;
    }else{
        $response = $response->withStatus(302);
        $response = $response->withHeader("Location", "/nologin");
        return $response;
    }
});

$app->get('/logout', function (Request $request, Response $response, array $args) use ($twig, $loginService) {
    
    $loginService->logout();
    $response = $response->withStatus(302);
    $response = $response->withHeader("Location", "/");
    return $response;
});

$app->get('/register', function (Request $request, Response $response, array $args) use ($twig) {
    $template = $twig->load('register.html');

    $response->getBody()->write(
        $template->render([])
    );
    return $response;
});

$app->post('/register', function (Request $request, Response $response, array $args) use ($twig, $userService) {
    
    $userName = $_POST['userId'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $user = $userService->register($userName, $name, $password);

    if($user){
        $response = $response->withStatus(302);
        $response = $response->withHeader("Location", "/");
        return $response;
    }else{
        $response = $response->withStatus(302);
        $response = $response->withHeader("Location", "/register");
        return $response;
    }
});

$app->get('/feed', function (Request $request, Response $response, array $args) use ($twig) {
    
    $template = $twig->load('feed.html');

    $response->getBody()->write(
        $template->render(['username' => $_SESSION['user']])
    );
    return $response;
});

$app->post('/newPost', function (Request $request, Response $response, array $args) use ($twig, $postService, $userService) {
    $userObject = $_SESSION['user'];
    $user = $userService->getUser($userObject);
    $content = $_POST['post'];
    $post = $postService->create($content, $user);
    
    if($post instanceof \Tuiter\Models\Post){
        $response = $response->withStatus(302);
        $response = $response->withHeader("Location", "/feed");
        return $response;
    }else{
        $response = $response->withStatus(302);
        $response = $response->withHeader("Location", "/noPost");
        return $response;
    }
});

$app->post('/user/me', function (Request $request, Response $response, array $args) use ($twig, $postService, $userService) {
    $userObject = $_SESSION['user'];
    $user = $userService->getUser($userObject);
    $postList = $postService->getAllPosts($user);
    
    $post=array();
    foreach($postList as $post){
        $post[]=array(
            'content'->getContent()
        );
    }
    
    $response->getBody()->write(
        $template->render(['me' => $post])
    );
    return $response;
    
});

$app->run();