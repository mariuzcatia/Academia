<?php

namespace Tuiter\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SearchController implements \Tuiter\Interfaces\Controller {

    public function config($app) {

        $app->post('/search', function (Request $request, Response $response, array $args) {
            
            $result = $request->getAttribute("userService")->getUser($_POST["userId"]);
            $_SESSION['friend']=$_POST["userId"];
            if(!($result instanceof \Tuiter\Models\UserNull)){

                $posts = $request->getAttribute("postService")->getAllPosts($result);
    
                foreach ($posts as $post) {
                    $post->likes = $request->getAttribute('likeService')->count($post);
                }
    
                $template = $request->getAttribute("twig")->load('/search.html');
                $response->getBody()->write(
                    $template->render(['posts' => $posts, 'user' => $request->getAttribute("user")->getName(),'login' => $request->getAttribute('login'), 'current_user'=> $userObject, 'friend'=> $_POST["userId"] ])
                );
                return $response;
            }else{
                $response->getBody()->write("usuario no existe");
                return $response;
            }
           
        });
    }
}