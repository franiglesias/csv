<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader\Mapper;

class DtoMapper extends RowMapper
{
    private $dtoClass;

    public function __construct($dtoClass)
    {
        $this->dtoClass = $dtoClass;
    }

    public function map(array $line, ?array $headers = null)
    {
        $this->assertHeaders($headers);
        $row = $this->buildRow($headers, $line);
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

        return unserialize($serializedDto, [$this->dtoClass]);
    }
}
