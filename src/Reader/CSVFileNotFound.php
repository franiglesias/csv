<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Reader;

use RuntimeException;

class CSVFileNotFound extends RuntimeException
{

    public static function failedPath(string $file): self
    {
        return new self(sprintf('File %s not found', $file));
    }
}
