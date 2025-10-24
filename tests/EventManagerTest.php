<?php

require_once __DIR__ . '/fixture/TestEventA.php';
require_once __DIR__ . '/fixture/TestEventB.php';

use PHPUnit\Framework\TestCase;
use Leopard\Events\EventManager;
use Leopard\Events\Tests\Fixture\TestEventA;
use Leopard\Events\Tests\Fixture\TestEventB;

class EventManagerTest extends TestCase
{
    public function testAddAndDispatchListenerA()
    {
        EventManager::addEvent(new TestEventA(), function ($e) {
            $e->handled = true;
        });
        $event = EventManager::doEvent(TestEventA::class);
        $this->assertTrue($event->handled);
    }

    public function testEditOtherObject()
    {
        EventManager::getProvider()->clearListeners();
        $obj = new \stdClass();
        $obj->value = 10;
        EventManager::addEvent(new TestEventA($obj), function ($e) {
            $e->obj->value = 20;
        });
        EventManager::doEvent(TestEventA::class);
        $this->assertEquals(20, $obj->value);
    }

    public function testMultipleListeners()
    {
        EventManager::getProvider()->clearListeners();
        EventManager::addEvent(new TestEventB(), function ($e) {
            $e->handled = 'first';
        });
        EventManager::addEvent(new TestEventB(), function ($e) {
            $e->handled = 'second';
        });
        $event = EventManager::doEvent(TestEventB::class);
        $this->assertEquals('second', $event->handled);
    }

    public function testNoListeners()
    {
        EventManager::getProvider()->clearListeners();
        $event = EventManager::doEvent(TestEventB::class);
        $this->assertFalse($event->handled);
    }

    public function testPublicMethods()
    {
        EventManager::getProvider()->clearListeners();
        EventManager::addEvent(new TestEventA(), [$this, 'updateObject']);
        $event = EventManager::doEvent(TestEventA::class);
        $this->assertTrue($event->updated);
    }

    public function testUpdatedObject()
    {
        EventManager::getProvider()->clearListeners();
        $obj = new \stdClass();
        $obj->count = 10;
        EventManager::addEvent(new TestEventA($obj), function ($e) {
            $this->assertEquals(15, $e->obj->count);
            $e->obj->count = 20;
        });
        $obj->count = 15;
        EventManager::doEvent(TestEventA::class);
        $this->assertEquals(20, $obj->count);
    }

    public function testRemoveListener()
    {
        EventManager::getProvider()->clearListeners();
        $obj = new \stdClass();
        $obj->value = 5;
        EventManager::addEvent(new TestEventA($obj), [$this, 'updateValueObject']);
        EventManager::removeEvent(new TestEventA($obj), [$this, 'updateValueObject']);
        EventManager::doEvent(TestEventA::class);
        $this->assertEquals(5, $obj->value);
    }

    public function updateObject(object $obj)
    {
        $obj->updated = true;
    }

    public static function updateValueObject(object $obj)
    {
        $obj->value += 5;
    }
}
