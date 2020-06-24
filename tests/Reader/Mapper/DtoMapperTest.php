<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Tests\Reader\Mapper;

use TalkingBit\Csv\Reader\Mapper\DtoMapper;
use TalkingBit\Csv\Tests\Reader\Mapper\Dto\RowDto;
use PHPUnit\Framework\TestCase;

class DtoMapperTest extends TestCase
{
    public function testShouldMapToDto(): void
    {
        $mapper = new DtoMapper(RowDto::class);
        $line = ['123', 'My Name'];
        $headers = ['id', 'name'];
        $expected = new RowDto();
        $expected->id = '123';
        $expected->name = 'My Name';

        $this->assertEquals($expected, $mapper->map($line, $headers));
    }

}
