<?php

namespace Tuiter\Services;

class ReshardingService{

    public function reshardingUser(array $collectionOld, array $collectionNew){
        $list=array();
        $userService = new UserService($collectionNew);
        for($i=0; $i<count($collectionOld); $i++){
            $list[] = $collectionOld[$i]->find([]);
            $collectionOld[$i]->deleteMany([]);
        }
        foreach($list as $document){
            foreach($document as $user){
                $userService->register($user['userId'], $user['name'], $user['password']);
            }
        }
        for($i=0; $i<count($collectionOld); $i++){
            $collectionOld[$i]->drop();        
        }
        return True;
    }
}
