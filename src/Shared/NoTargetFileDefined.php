<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Shared;

use LogicException;

class NoTargetFileDefined extends LogicException
{
    public static function forWriting(): self
    {
        return new self('You forgot to define a target file for writing.');
    }

    public static function forReading(): self
    {
        return new self('You forgot to define a target file for reading.');
    }
}
