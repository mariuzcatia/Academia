<?php

namespace SQ\Interfaces;

interface Queue {

    public function put($element);
    public function get();
    public function size();
    
}