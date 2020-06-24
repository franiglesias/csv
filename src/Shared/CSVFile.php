<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Shared;

use RuntimeException;
use SplFileObject;
use TalkingBit\Csv\Reader\CSVFileNotFound;

class CSVFile
{
    /**
     * @var SplFileObject
     */
    private $fileObject;
    /**
     * @var CSVSettings
     */
    private $settings;

    public function __construct(SplFileObject $fileObject, CSVSettings $settings)
    {
        $this->fileObject = $fileObject;
        $this->settings = $settings;
    }

    public static function forWriting(string $pathToFile): self
    {
        $fileObject = new SplFileObject($pathToFile, 'w');

        return new self($fileObject, CSVSettings::default());
    }

    public static function forReading(string $pathToFile): self
    {
        try {
            $fileObject = new SplFileObject($pathToFile);
            $fileObject->setFlags(SplFileObject::READ_CSV);
            $fileObject->setFlags(SplFileObject::SKIP_EMPTY);
        } catch (RuntimeException $fileNotFound) {
            throw CSVFileNotFound::failedPath($pathToFile);
        }

        return new self($fileObject, CSVSettings::default());
    }

    public function write(array $row)
    {
        return $this->fileObject->fputcsv(
            $row,
            $this->settings->delimiter(),
            $this->settings->enclosure()
        );
    }

    public function read()
    {
        return $this->fileObject->fgetcsv(
            $this->settings->delimiter(),
            $this->settings->enclosure()
        );
    }

    public function setDelimiter(string $delimiter): void
    {
        $this->settings = $this->settings->changeDelimiter($delimiter);
    }

    public function setEnclosure(string $enclosure): void
    {
        $this->settings = $this->settings->changeEnclosure($enclosure);
    }

    public function valid(): bool
    {
        return $this->fileObject->valid();
    }

}
