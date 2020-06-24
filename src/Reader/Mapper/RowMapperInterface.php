<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader\Mapper;

interface RowMapperInterface
{
    public function map(array $line, ?array $headers = null);
}
