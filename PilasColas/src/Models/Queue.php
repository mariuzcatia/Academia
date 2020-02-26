<?php

namespace SQ\Models;

class Queue {
    private $list=array();
    
    public function put($element){
        $this->list[]=$element;
        if(in_array($element, $this->list)){
            return True;
        }else{
            return False;
        }
    }

    public function get(){
        if(!empty($this->list)){
            $element = $this->list[0];
            unset($this->list[0]);
            $this->list = array_values($this->list);
            return $element;
        }else{
            return False;
        }
    }

    public function size(){
        return count($this->list);
    }

}
