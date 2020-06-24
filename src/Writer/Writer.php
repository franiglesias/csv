<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Writer;

use SplFileObject;
use TalkingBit\Csv\Shared\NoTargetFileDefined;

class Writer
{
    /** @var SplFileObject */
    private $targetFile;
    private $delimiter = ',';
    private $enclosure = '"';
    private $firstRow = true;

    public function writeRow($row): void
    {
        $this->assertFileForWriting();
        $this->targetFile->setCsvControl(
            $this->delimiter,
            $this->enclosure
        );
        if (is_object($row)) {
            $row = (array)$row;
        }
        if ($this->firstRow && !is_numeric(key($row))) {
            $headers = array_keys($row);
            $this->targetFile->fputcsv($headers);
            $this->firstRow = false;
        }
        $this->targetFile->fputcsv($row);
    }

    public function toFile(string $pathToFile): self
    {
        $this->targetFile = new SplFileObject($pathToFile, 'w');

        return $this;
    }

    private function assertFileForWriting(): void
    {
        if (null === $this->targetFile) {
            throw NoTargetFileDefined::forWriting();
        }
    }

    public function withDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    public function withEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }
}
