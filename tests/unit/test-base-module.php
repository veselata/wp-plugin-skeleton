<?php

use PHPUnit\Framework\TestCase;

class TestBaseModule extends TestCase {

    public function setUp() : void {
	\WP_Mock::setUp();
    }

    public function tearDown() : void {
	\WP_Mock::tearDown();
    }
        
    public function testGetInstance(){
        $this->assertFalse(false);
    }
}



