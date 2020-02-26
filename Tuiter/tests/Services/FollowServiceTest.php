<?php

namespace TestTuiter\Services;

use \Tuiter\Services\FollowService;

final class FollowServiceTest extends \PHPUnit\Framework\TestCase {
    
    protected function setUp(): void{
        $connection = new \MongoDB\Client("mongodb://localhost");
        $collection = $connection->FollowServiceTest->FollowTest;
        $collection->drop();
        $collectionUserService  = $connection->FollowServiceTest->UserService;
        $collectionUserService->drop();
        $this->us = new \Tuiter\Services\UserService(array($collectionUserService));
        $this->fs = new \Tuiter\Services\FollowService($collection, $this->us);
        $this->us->register("eliel", "Heber", "123456");
        $this->us->register("edu", "Edward", "123456");
        $this->us->register("diego", "Edward", "123456");
        $this->us->register("nico", "Edward", "123456");
        $this->us->register("rober", "Edward", "123456");
        $this->us->register("fran", "Edward", "123456");
        $this->eliel = $this->us->getUser("eliel");
        $this->edu = $this->us->getUser("edu");
        $this->diego = $this->us->getUser("diego");
        $this->nico = $this->us->getUser("nico");
        $this->rober = $this->us->getUser("rober");
        $this->fran = $this->us->getUser("fran");
    }

    public function testClassExists(){
        $this->assertTrue(class_exists("\Tuiter\Services\FollowService"));
    }
    
    public function testFollow(){
        $this->assertTrue($this->fs->follow($this->eliel,$this->edu));
        $this->assertFalse($this->fs->follow($this->eliel,$this->edu));
    }

    public function testGetFollowers(){
        $this->assertTrue($this->fs->follow($this->eliel,$this->edu));
        $this->assertCount(1,$this->fs->getFollowers($this->edu));
    }

    public function testGetMasFollowers(){
        $this->assertTrue($this->fs->follow($this->eliel,$this->edu));
        $this->assertTrue($this->fs->follow($this->diego,$this->edu));
        $this->assertTrue($this->fs->follow($this->nico,$this->edu));
        $this->assertCount(3,$this->fs->getFollowers($this->edu));
    }

    public function testGetFollowed(){
        $this->assertTrue($this->fs->follow($this->eliel,$this->edu));
        $this->assertCount(1,$this->fs->getFollowed($this->eliel));
    }

    public function testGetMasFollowed(){
        $this->assertTrue($this->fs->follow($this->eliel,$this->fran));
        $this->assertTrue($this->fs->follow($this->eliel,$this->nico));
        $this->assertTrue($this->fs->follow($this->eliel,$this->rober));
        $this->assertCount(3,$this->fs->getFollowed($this->eliel));
    }

    public function testUnfollow(){
        $this->assertTrue($this->fs->follow($this->eliel,$this->edu));
        $this->assertCount(1, $this->fs->getFollowers($this->edu));
        $this->assertTrue($this->fs->unFollow($this->eliel,$this->edu));
    }
}