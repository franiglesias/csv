<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader\Mapper;

class AssocMapper extends RowMapper
{

    public function map(array $line, ?array $headers = null): array
    {
        $this->assertHeaders($headers);

        return $this->buildRow($headers, $line);
    }

}
