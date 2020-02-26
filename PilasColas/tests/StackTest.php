<?php

namespace Test;

use \SQ\Models\Stack;

final class StackTest extends \PHPUnit\Framework\TestCase {

    public function testClassExists() {
        $this->assertTrue(class_exists("\SQ\Models\Stack"));
    }

    public function testPushElement() {
        $stack = new Stack();
        $this->assertTrue($stack->push("Hola"));
    }

    public function testPushTwoElement() {
        $stack = new Stack();
        $this->assertTrue($stack->push("Hola"));
        $this->assertTrue($stack->push("Marianny"));
    }

    public function testPopElement() {
        $stack = new Stack();
        $this->assertTrue($stack->push("Hola"));
        $this->assertEquals($stack->pop(), "Hola");
    }

    public function testPopElemenWithTwo() {
        $stack = new Stack();
        $this->assertTrue($stack->push("Hola"));
        $this->assertTrue($stack->push("Marianny"));
        $this->assertEquals($stack->pop(), "Marianny");
    }

    public function testPopElemenWithThree() {
        $stack = new Stack();
        $this->assertTrue($stack->push("Hola"));
        $this->assertTrue($stack->push("Marianny"));
        $this->assertTrue($stack->push("Uzcatia"));
        $this->assertEquals($stack->pop(), "Uzcatia");
    }

    public function testPopTwoElemenWithThree() {
        $stack = new Stack();
        $this->assertTrue($stack->push("Hola"));
        $this->assertTrue($stack->push("Marianny"));
        $this->assertTrue($stack->push("Uzcatia"));
        $this->assertEquals($stack->pop(), "Uzcatia");
        $this->assertEquals($stack->pop(), "Marianny");
    }

    public function testPopElementFalse() {
        $stack = new Stack();
        $this->assertFalse($stack->pop());
    }

    public function testEmpty() {
        $stack = new Stack();
        $this->assertTrue($stack->push("Hola"));
        $this->assertTrue($stack->push("Marianny"));
        $this->assertTrue($stack->push("Uzcatia"));
        $this->assertFalse($stack->empty());
    }

    public function testSNoEmpty() {
        $stack = new Stack();
        $this->assertTrue($stack->empty());
    }

    public function testEmptyAfterPush() {
        $stack = new Stack();
        $this->assertTrue($stack->empty());
        $this->assertTrue($stack->push("Hola"));
        $this->assertFalse($stack->empty());
    }

    public function testEmptyAfterPushAndPop() {
        $stack = new Stack();
        $this->assertTrue($stack->empty());
        $this->assertTrue($stack->push("Hola"));
        $this->assertFalse($stack->empty());
        $this->assertEquals($stack->pop(), "Hola");
        $this->assertTrue($stack->empty());
    }
}