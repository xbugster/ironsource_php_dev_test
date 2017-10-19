<?php

use PHPUnit\Framework\TestCase;
use Core\Registry;

class RegistryTest extends TestCase
{
    public function testGetAndSet() {
        $registry = new Registry();

        $this->assertFalse($registry->set(), 'Registry set returned unexpect response for blank.');
        $this->assertFalse($registry->set(null, new stdClass()), 'Registry set returned unexpected on blank key.');
        $this->assertNull($registry->get(), 'Registry get returned not expected value on blank get call.');

        $this->assertTrue($registry->set('x', new stdClass()), 'Registry set properly called returned not true.');
        $this->assertNotNull($registry->get('x'), 'Registry get returned null when object expected');

        $this->assertTrue($registry->set('z', null), 'Registry set should allow null value for any key.');
        $this->assertNull($registry->get('z'), 'Registry knows to return null objects.');
    }
}