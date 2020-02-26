<?php

namespace SQ\Interfaces;

interface Stack {

    public function push($element);
    public function pop();
    public function empty();
}