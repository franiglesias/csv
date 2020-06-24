<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Tests\Reader\Mapper;

use PHPUnit\Framework\TestCase;
use TalkingBit\Csv\Reader\Mapper\AssocMapper;
use TalkingBit\Csv\Reader\Mapper\NoHeadersFound;

class AssocMapperTest extends TestCase
{

    public function testShouldMapToAssociativeArrayUsingHeaders(): void
    {
        $mapper = new AssocMapper();

        $line = ['a', 'b', '123'];
        $headers = ['first', 'second', 'third'];
        $expected = ['first' => 'a', 'second' => 'b', 'third' => '123'];

        $this->assertEquals($expected, $mapper->map($line, $headers));
    }

    public function testShouldRequireHeaders(): void
    {
        $mapper = new AssocMapper();

        $line = ['a', 'b', '123'];
        $headers = null;

        $this->expectException(NoHeadersFound::class);
        $mapper->map($line, $headers);


    }

}
