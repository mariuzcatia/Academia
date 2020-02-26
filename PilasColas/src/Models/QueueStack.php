<?php

namespace SQ\Models;

class QueueStack {
    private $stackOne;
    private $stackTwo;
    
    public function __construct(){
        $this->stackOne = new \SQ\Models\Stack;
        $this->stackTwo = new \SQ\Models\Stack;
    }

    public function put($element){
        while(!($this->stackOne->empty())){
            $x = $this->stackOne->pop();
            $this->stackTwo->push($x);
        }
        $this->stackOne->push($element);
        return True;       
    }

    public function get(){
        while(!($this->stackTwo->empty())){
            $x = $this->stackTwo->pop();
            $this->stackOne->push($x);
        }
        $element = $this->stackOne->pop();
        return $element;
    }

    public function size(){
        $count=0;
        while(!($this->stackTwo->empty())){
            $x = $this->stackTwo->pop();
            $this->stackOne->push($x);;
        }
        while(!($this->stackOne->empty())){
            $x = $this->stackOne->pop();
            $this->stackTwo->push($x);;
            $count+=1;
        }
        return $count;
    }
}