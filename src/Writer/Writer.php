<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Writer;

use TalkingBit\Csv\Shared\CSVFile;
use TalkingBit\Csv\Shared\NoTargetFileDefined;

class Writer
{
    /** @var CSVFile */
    private $targetFile;
    /** @var bool */
    private $firstRow = true;

    /** @param array<mixed> $row */
    public function writeRow($row): void
    {
        $this->assertCSVFile();

        $row = (array)$row;

        if ($this->firstRow && ! is_numeric(key($row))) {
            $this->writeHeaders($row);
        }

        $this->targetFile->write($row);
    }

    public function toFile(string $pathToFile): self
    {
        $this->targetFile = CSVFile::forWriting($pathToFile);

        return $this;
    }

    private function assertCSVFile(): void
    {
        if (null === $this->targetFile) {
            throw NoTargetFileDefined::forWriting();
        }
    }

    public function withDelimiter(string $delimiter): self
    {
        $this->targetFile->setDelimiter($delimiter);

        return $this;
    }

    public function withEnclosure(string $enclosure): self
    {
        $this->targetFile->setEnclosure($enclosure);

        return $this;
    }

    /**
     * @param array<mixed> $row
     */
    private function writeHeaders(array $row): void
    {
        $headers = array_keys($row);
        $this->targetFile->write($headers);
        $this->firstRow = false;
    }
}
