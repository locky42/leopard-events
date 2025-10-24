<?php

namespace Leopard\Events\Tests\Fixture;

class TestEventA {
    public function __construct(public ?object $obj = null) {}
}
