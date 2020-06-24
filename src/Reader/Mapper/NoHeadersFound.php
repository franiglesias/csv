<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader\Mapper;

use RuntimeException;

class NoHeadersFound extends RuntimeException
{

    public static function required(RowMapperInterface $mapper): self
    {
        return new self(sprintf('Headers could not be found for the file and are required by %s', get_class($mapper)));
    }
}
