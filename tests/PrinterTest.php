<?php

use PHPUnit\Framework\TestCase;
use Phpsafari\Utils\Printer;

class PrinterTest extends TestCase
{

    /**
     * @test
     * @group printer
     *
     */
    public function can_print_vars()
    {
        $this->assertEquals('null', Printer::toString(null));
        $this->assertEquals('true', Printer::toString(true));
        $this->assertEquals('false', Printer::toString(false));
        $this->assertEquals('1', Printer::toString(1));
        $this->assertEquals('[hello=world]', Printer::toString(['hello' => 'world']));
        $this->assertEquals('[hello.inception=world]', Printer::toString(['hello' => ['inception' => 'world']]));
    }
}