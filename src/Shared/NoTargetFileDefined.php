<?php
declare (strict_types=1);

namespace TalkingBit\Csv\Shared;

use LogicException;

class NoTargetFileDefined extends LogicException
{
    public static function forWriting(): self
    {
        return new self('You need to define a target file for writing by calling the toFile method first of all.');
    }

    public static function forReading(): self
    {
        return new self('You forgot to define a target file for reading by calling the fromFile method first of all.');
    }
}
