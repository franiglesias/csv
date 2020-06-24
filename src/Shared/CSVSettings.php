<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Shared;

class CSVSettings
{

    /**
     * @var string
     */
    private $delimiter;
    /**
     * @var string
     */
    private $enclosure;

    private function __construct(string $delimiter, string $enclosure)
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }

    public static function default(): self
    {
        return new self(',', '"');
    }

    public function delimiter(): string
    {
        return $this->delimiter;
    }

    public function enclosure(): string
    {
        return $this->enclosure;
    }

    public function changeDelimiter(string $delimiter): self
    {
        return new CSVSettings($delimiter, $this->enclosure);
    }

    public function changeEnclosure(string $enclosure): self
    {
        return new CSVSettings($this->delimiter, $enclosure);
    }
}
