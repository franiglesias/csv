<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Tests\Reader\Mapper;

use PHPUnit\Framework\TestCase;
use TalkingBit\Csv\Reader\Mapper\ArrayMapper;

class ArrayMapperTest extends TestCase
{

    public function testShouldMapToPlainArray(): void
    {
        $mapper = new ArrayMapper();

        $line = ['a', 'b', '123'];
        $expected = ['a', 'b', '123'];

        $this->assertEquals($expected, $mapper->map($line));
    }
}
