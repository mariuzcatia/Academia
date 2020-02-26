<?php

namespace TestTuiter\Services;

use \Tuiter\Services\ReshardingService;
use \Tuiter\Services\UserService;

final class ReshardingServiceTest extends \PHPUnit\Framework\TestCase {
    private $collection;

    protected function setUp(): void{
        $conn = new \MongoDB\Client("mongodb://localhost");
        $list[]=$conn->tuiter1->usuarios;
        $list[]=$conn->tuiter2->usuarios;
        $list[]=$conn->tuiter3->usuarios;
        $list[]=$conn->tuiter4->usuarios;
        $list[]=$conn->tuiter5->usuarios;
        $list[]=$conn->tuiter6->usuarios;
        $list[]=$conn->tuiter7->usuarios;
        $list[]=$conn->tuiter8->usuarios;
        $list[]=$conn->tuiter9->usuarios;
        $list[]=$conn->tuiter10->usuarios;        
        $this->collection = $list;
        for($i=0; $i<count($this->collection); $i++){
            $this->collection[$i]->drop();        
        }
    }

    public function testExisteClase() {
        $this->assertTrue(class_exists("\Tuiter\Services\ReshardingService"));
    }

    public function testRegisterUsers(){
        $collectionOld =array($this->collection[0], $this->collection[1]);
        $collectionNew =array($this->collection[4], $this->collection[5]);
        $us = new UserService($collectionOld);
        for($i=0; $i<1000; $i++) {
            $user = $us->register("mari".$i, "marianny", "1234");
        }
        for($i=0; $i<1000; $i++) {
            $userExpect=$us->getUser("mari".$i)->getUserId();
            $this->assertEquals($userExpect, "mari".$i);
        }
        $resharding = new ReshardingService();
        $resharding->reshardingUser($collectionOld, $collectionNew);
        
        $usnew = new UserService($collectionNew);
        for($i=0; $i<1000; $i++) {
            $userExpect=$usnew->getUser("mari".$i)->getUserId();
            $this->assertEquals($userExpect, "mari".$i);
        }        
    }

    public function testRegisterUsersMil(){
        $collectionNew =array($this->collection[0], $this->collection[1]);
        $us = new UserService($this->collection);
        for($i=0; $i<1000; $i++) {
            $user = $us->register("mari".$i, "marianny", "1234");
        }
        for($i=0; $i<1000; $i++) {
            $userExpect=$us->getUser("mari".$i)->getUserId();
            $this->assertEquals($userExpect, "mari".$i);
        }
        $resharding = new ReshardingService();
        $resharding->reshardingUser($this->collection, $collectionNew);
        
        $usnew = new UserService($collectionNew);
        for($i=0; $i<1000; $i++) {
            $userExpectTwo=$usnew->getUser("mari".$i)->getUserId();
            $this->assertEquals($userExpectTwo, "mari".$i);
        }        
    }
}