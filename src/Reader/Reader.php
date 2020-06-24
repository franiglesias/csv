<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader;

use Generator;
use RuntimeException;
use SplFileObject;
use TalkingBit\Csv\Reader\Mapper\ArrayMapper;
use TalkingBit\Csv\Reader\Mapper\AssocMapper;
use TalkingBit\Csv\Shared\NoTargetFileDefined;

class Reader
{
    private $useHeaders = false;
    private $mapper;
    private $delimiter = ';';
    private $enclosure = '"';
    private $targetFile;

    public function readAll(): Generator
    {
        if (null === $this->targetFile) {
            throw NoTargetFileDefined::forReading();
        }

        $this->targetFile->setFlags(SplFileObject::READ_CSV);
        $this->targetFile->setFlags(SplFileObject::SKIP_EMPTY);
        $this->targetFile->setCsvControl(
            $this->delimiter,
            $this->enclosure
        );

        $headers = null;

        $this->obtainMapper();

        while ($this->targetFile->valid()) {
            $line = $this->targetFile->fgetcsv();
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
        $this->targetFile = $this->obtainFile($pathToFile);

        return $this;
    }

    private function obtainFile(
        string $file
    ): SplFileObject {
        try {
            $splFileObject = new SplFileObject($file);
        } catch (RuntimeException $fileNotFound) {
            throw CSVFileNotFound::failedPath($file);
        }

        return $splFileObject;
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
        $this->delimiter = $delimiter;

        return $this;
    }

    public function withEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }
}
