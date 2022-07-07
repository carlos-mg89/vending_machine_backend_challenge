<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class SimpleTest extends TestCase
{
    function testGivenAnEmptyTestWhenRunningThisTestAssertThatTrueIsTrue()
    {
        $this->assertTrue(true);
    }
}
