<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader\Mapper;

abstract class RowMapper implements RowMapperInterface
{

    abstract public function map(array $line, ?array $headers = null);

    protected function assertHeaders(?array $headers): void
    {
        if (null === $headers) {
            throw NoHeadersFound::required($this);
        }
    }

    protected function buildRow(array $headers, array $line): array
    {
        return array_combine($headers, $line);
    }
}
