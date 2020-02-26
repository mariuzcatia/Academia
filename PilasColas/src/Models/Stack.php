<?php

namespace SQ\Models;

class Stack {
    private $list=array();
    
    public function push($element){
        $this->list[]=$element;
        if(in_array($element, $this->list)){
            return True;
        }else{
            return False;
        }
    }

    public function pop(){
        if(!$this->empty()){
            $element = array_pop($this->list);
            return $element;
        }else{
            return False;
        }
    }

    public function empty(){
        if(empty($this->list)){
            return True;
        }        
        return False;
    }
}
