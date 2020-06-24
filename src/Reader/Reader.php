<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader;

use Generator;
use SplFileObject;
use TalkingBit\Csv\Reader\Mapper\ArrayMapper;
use TalkingBit\Csv\Reader\Mapper\AssocMapper;
use TalkingBit\Csv\Reader\Mapper\RowMapperInterface;
use TalkingBit\Csv\Shared\CSVFile;
use TalkingBit\Csv\Shared\NoTargetFileDefined;

class Reader
{
    /** @var CSVFile */
    private $targetFile;
    /** @var RowMapperInterface */
    private $mapper;
    private $useHeaders = false;

    public function readAll(): Generator
    {
        $this->assertCSVFile();

        $this->obtainMapper();

        $headers = null;

        while ($this->targetFile->valid()) {
            $line = $this->targetFile->read();
            if ($this->useHeaders) {
                $headers = $line;
                $this->useHeaders = false;

                continue;
            }

            yield $this->mapper->map($line, $headers);
        }
    }

    public function fromFile(string $pathToFile): self
    {
        $this->targetFile = CSVFile::forReading($pathToFile);

        return $this;
    }

    private function obtainMapper(): void
    {
        if (null === $this->mapper) {
            $this->mapper = $this->useHeaders ? new AssocMapper() : new ArrayMapper();
        }
    }

    public function withHeaders(): self
    {
        $this->useHeaders = true;

        return $this;
    }

    public function usingMapper(ArrayMapper $mapper): self
    {
        $this->mapper = $mapper;

        return $this;
    }

    public function withDelimiter(string $delimiter): self
    {
        $this->assertCSVFile();

        $this->targetFile->setDelimiter($delimiter);

        return $this;
    }

    public function withEnclosure(string $enclosure): self
    {
        $this->assertCSVFile();

        $this->targetFile->setEnclosure($enclosure);

        return $this;
    }

    private function assertCSVFile(): void
    {
        if (null === $this->targetFile) {
            throw NoTargetFileDefined::forReading();
        }
    }
}
