<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader\Mapper;

use TalkingBit\Csv\Tests\Reader\Mapper\Dto\RowDto;

class DtoMapper implements RowMapperInterface
{
    public function __construct($dtoClass)
    {
        $this->dtoClass = $dtoClass;
    }

    public function map(array $line, ?array $headers = null)
    {
        $row = array_combine($headers, $line);
        $serializedStdClass = $this->convertIntoSerializedStdClass($row);

        return $this->deserializeToDesiredDto($serializedStdClass);
    }

    private function convertIntoSerializedStdClass(array $row): string
    {
        return serialize((object)$row);
    }

    private function deserializeToDesiredDto(string $serializedStdClass)
    {
        $replacement = sprintf('O:%s:"%s":', strlen($this->dtoClass), $this->dtoClass);

        $serializedDto = preg_replace('/^O:8:"stdClass":/', $replacement, $serializedStdClass);

        return unserialize($serializedDto);
    }
}
