<?php

namespace Test;

use \SQ\Models\Queue;

final class QueueTest extends \PHPUnit\Framework\TestCase {

    public function testClassExists() {
        $this->assertTrue(class_exists("\SQ\Models\Queue"));
    }

    public function testPutElement() {
        $queue = new Queue();
        $this->assertTrue($queue->put("Hola"));
    }

    public function testPutTwoElement() {
        $queue = new Queue();
        $this->assertTrue($queue->put("Hola"));
        $this->assertTrue($queue->put("Marianny"));
    }

    public function testGetElement() {
        $queue = new Queue();
        $this->assertTrue($queue->put("Hola"));
        $this->assertEquals($queue->get(), "Hola");
    }

    public function testGetElemenWithTwo() {
        $queue = new Queue();
        $this->assertTrue($queue->put("Hola"));
        $this->assertTrue($queue->put("Marianny"));
        $this->assertEquals($queue->get(), "Hola");
    }

    public function testGetElemenWithThree() {
        $queue = new Queue();
        $this->assertTrue($queue->put("Hola"));
        $this->assertTrue($queue->put("Marianny"));
        $this->assertTrue($queue->put("Uzcatia"));
        $this->assertEquals($queue->get(), "Hola");
    }

    public function testGetTwoElemenWithThree() {
        $queue = new Queue();
        $this->assertTrue($queue->put("Hola"));
        $this->assertTrue($queue->put("Marianny"));
        $this->assertTrue($queue->put("Uzcatia"));
        $this->assertEquals($queue->get(), "Hola");
        $this->assertEquals($queue->get(), "Marianny");
    }

    public function testGetElementFalse() {
        $queue = new Queue();
        $this->assertFalse($queue->get());
    }

    public function testSize() {
        $queue = new Queue();
        $this->assertTrue($queue->put("Hola"));
        $this->assertTrue($queue->put("Marianny"));
        $this->assertTrue($queue->put("Uzcatia"));
        $this->assertEquals($queue->size(), 3);
    }

    public function testSizeAfterGet() {
        $queue = new Queue();
        $this->assertTrue($queue->put("Hola"));
        $this->assertTrue($queue->put("Marianny"));
        $this->assertTrue($queue->put("Uzcatia"));
        $this->assertEquals($queue->size(), 3);
        $this->assertEquals($queue->get(), "Hola");
        $this->assertEquals($queue->size(), 2);
        $this->assertEquals($queue->get(), "Marianny");
        $this->assertEquals($queue->size(), 1);
        $this->assertEquals($queue->get(), "Uzcatia");
    }
}