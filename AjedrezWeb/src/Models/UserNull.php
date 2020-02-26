<?php

namespace Ajedrez\Models;

class UserNull extends User {

    public function getUserId(): string {
        return "Null";
    }

    public function getName(): string {
        return "Null";
    }

    public function getPassword(): string {
        return "Null";
    }
}