<?php

namespace Tuiter\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserNameController implements \Tuiter\Interfaces\Controller {
    public function config($app) {
        $app->get('/fix/{username}', function (Request $request, Response $response, array $args) {
            $userId = $args['username'];
            $userObject = $request->getAttribute("userService")->getUser($userId);
            
            if(!($userObject instanceof \Tuiter\Models\UserNull)){

                $posts = $request->getAttribute("postService")->getAllPosts($userObject);

                foreach ($posts as $post) {
                    $post->likes = $request->getAttribute('likeService')->count($post);
                }
    
                $template = $request->getAttribute("twig")->load('/feed.html');
                $response->getBody()->write(
                    $template->render(['posts' => $posts, 'user' => $request->getAttribute("user")->getName(),'login' => $request->getAttribute('login'), 'current_user'=> $userObject])
                );
                return $response;
            }else{
                $response->getBody()->write("error");
                return $response;
            }
        });
    }
}