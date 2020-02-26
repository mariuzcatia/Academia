<?php

namespace Test;

use \SQ\Models\QueueStack;

final class QueueStackTest extends \PHPUnit\Framework\TestCase {

    public function testClassExists() {
        $this->assertTrue(class_exists("\SQ\Models\QueueStack"));
    }

    public function testPutElement() {
        $queueStack = new QueueStack();
        $this->assertTrue($queueStack->put("Hola"));
    }

    public function testPutTwoElement() {
        $queueStack = new QueueStack();
        $this->assertTrue($queueStack->put("Hola"));
        $this->assertTrue($queueStack->put("Marianny"));
    }

    public function testGetElement() {
        $queueStack = new QueueStack();
        $this->assertTrue($queueStack->put("Hola"));
        $this->assertEquals($queueStack->get(), "Hola");
    }

    public function testGetElemenWithTwo() {
        $queueStack = new QueueStack();
        $this->assertTrue($queueStack->put("Hola"));
        $this->assertTrue($queueStack->put("Marianny"));
        $this->assertEquals($queueStack->get(), "Hola");
    }

    public function testGetElemenWithThree() {
        $queueStack = new QueueStack();
        $this->assertTrue($queueStack->put("Hola"));
        $this->assertTrue($queueStack->put("Marianny"));
        $this->assertTrue($queueStack->put("Uzcatia"));
        $this->assertEquals($queueStack->get(), "Hola");
    }

    public function testGetTwoElemenWithThree() {
        $queueStack = new QueueStack();
        $this->assertTrue($queueStack->put("Hola"));
        $this->assertTrue($queueStack->put("Marianny"));
        $this->assertTrue($queueStack->put("Uzcatia"));
        $this->assertEquals($queueStack->get(), "Hola");
        $this->assertEquals($queueStack->get(), "Marianny");
    }

    public function testGetElementFalse() {
        $queueStack = new QueueStack();
        $this->assertFalse($queueStack->get());
    }

    public function testSize() {
        $queueStack = new QueueStack();
        $this->assertTrue($queueStack->put("Hola"));
        $this->assertTrue($queueStack->put("Marianny"));
        $this->assertTrue($queueStack->put("Uzcatia"));
        $this->assertEquals($queueStack->size(), 3);
    }

    public function testSizeAfterGet() {
        $queueStack = new QueueStack();
        $this->assertTrue($queueStack->put("Hola"));
        $this->assertTrue($queueStack->put("Marianny"));
        $this->assertTrue($queueStack->put("Uzcatia"));
        $this->assertEquals($queueStack->size(), 3);
        $this->assertEquals($queueStack->get(), "Hola");
        $this->assertEquals($queueStack->size(), 2);
        $this->assertEquals($queueStack->get(), "Marianny");
        $this->assertEquals($queueStack->size(), 1);
        $this->assertEquals($queueStack->get(), "Uzcatia");
    }
}