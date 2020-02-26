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
$userService = new \Ajedrez\Services\UserService(array($mongoconn->ajedrez->users));
$loginService = new \Ajedrez\Services\LoginService($userService);


$app = AppFactory::create();

$app->add(function($serverRequest, $requestHandler) use ($twig, $loginService, $userService) {

    $user = $loginService->getLoggedUser();
    $serverRequest = $serverRequest->withAttribute("user", $user);
    
    $serverRequest = $serverRequest->withAttribute("twig", $twig);
    $serverRequest = $serverRequest->withAttribute("userService", $userService);
    $serverRequest = $serverRequest->withAttribute("loginService", $loginService);
    
    return $requestHandler->handle($serverRequest);
});

$app->get('/', function (Request $request, Response $response, array $args) use ($twig) {
    
    $template = $twig->load('index.html');
    $response->getBody()->write(
        $template->render([])
    );
    return $response;
});

$app->post('/login', function (Request $request, Response $response, array $args) {
    
    $login = $request->getAttribute("loginService")->login($_POST['usuario'], $_POST['clave']);
    if (!($login instanceof \Ajedrez\Models\UserNull)){
        
        $response = $response->withStatus(302);
        return $response->withHeader("Location", "/juego");
    }else{
        $response->getBody()->write("error");
        return $response;
    }

    return $response;
});

$app->get('/registro', function (Request $request, Response $response, array $args) {
    
    $template = $request->getAttribute("twig")->load('registro.html');
    $response->getBody()->write(
        $template->render([])
    );
    return $response;
});

$app->get('/juego', function (Request $request, Response $response, array $args) {
        
    $template = $request->getAttribute("twig")->load('juego.html');
    $response->getBody()->write(
        $template->render(['user' => $request->getAttribute("user")->getName(), 'login' => $request->getAttribute('login')])
    );
    return $response;
});

$app->post('/registro', function (Request $request, Response $response, array $args) {
            
    $result = $request->getAttribute("userService")->register($_POST["userId"],$_POST['name'],$_POST["pass"]);
    $response = $response->withStatus(302);

    if($result){
        return $response->withHeader("Location", "/");
    }
    return $response;
});

$app->get('/logout', function (Request $request, Response $response, array $args) {
    $request->getAttribute("loginService")->logout();
    $response = $response->withStatus(302);
    return $response->withHeader("Location", "/");
});

$app->run();