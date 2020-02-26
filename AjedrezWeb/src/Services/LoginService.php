<?php

namespace Ajedrez\Services;

class LoginService {

    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function logout(){
        unset($_SESSION['login']);
        unset($_SESSION['user']);

    }

    public function login(string $userId, string $password){
        $user = $this->userService->getUser($userId);
        $pass = $user->getPassword($password);
        if ($user->getUserId() === $userId) {
            if ($pass === $password){
                $_SESSION['login']=true;
                $_SESSION['user'] = $userId;
                return $user;
            } else {
                return new \Ajedrez\Models\UserNull("","","");
            }
        }
        return $user;
    }

    public function getLoggedUser() {
        if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
            $user = $this->userService->getUser($_SESSION['user']);
            return $user;
        }
        return new \Ajedrez\Models\UserNull("", "", "");
    }
    
}