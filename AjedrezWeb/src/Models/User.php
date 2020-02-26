<?php

namespace Ajedrez\Models;

class User {

    protected $userId;
    protected $name;
    protected $password;

    public function __construct(string $userId, string $name, string $password) {
        $this->userId = $userId;
        $this->name = $name;
        $this->password = $password;
    }

    public function getUserId(): string {
        return $this->userId;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPassword(): string {
        return $this->password;
    }
}