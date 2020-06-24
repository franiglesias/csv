<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader\Mapper;

class AssocMapper implements RowMapperInterface
{

    public function map(array $line, ?array $headers = null): array
    {
        if (null !== $headers) {
            return array_combine($headers, $line);
        }
        return $line;
    }
}
