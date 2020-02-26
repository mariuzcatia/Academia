<?php

namespace Ajedrez\Services;

class UserService {

    private $collections;

    public function __construct(array $collections){
        $this->collections = $collections;
    }
    
    public function register(string $userId, string $name, string $password) {
        $user = $this->getUser($userId);
        $id = md5($userId);
        $numero = 0;
        for ($i=0; $i<strlen($userId); $i++){
            $numero+=ord($id[$i]);
        }
        $db = $numero % count($this->collections);
        if($user instanceof \Ajedrez\Models\UserNull){
            $usuarios= array();
            $usuarios['userId']= $userId;
            $usuarios['name']= $name;
            $usuarios['password']=$password;
            $this->collections[$db]->insertOne($usuarios);
            return true;
        } else {
            return false;
        }
    }
    public function getUser($userId){
        $id = md5($userId);
        $numero = 0;
        for ($i=0; $i<strlen($userId); $i++){
            $numero+=ord($id[$i]);
        }
        $db = $numero % count($this->collections);
        $cursor= $this->collections[$db]->findOne(['userId'=> $userId]);
        if (is_null($cursor)){
            $user = new \Ajedrez\Models\UserNull('','','');
            return $user;
        }
        $user = new \Ajedrez\Models\User($cursor['userId'],$cursor['name'], $cursor['password']);
        return $user;
    }
}