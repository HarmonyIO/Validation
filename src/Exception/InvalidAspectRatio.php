<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

class InvalidAspectRatio extends Exception
{
    private const MESSAGE_TEMPLATE = 'The aspect ratio (`%s`) could not be parsed.';

    public function __construct(string $aspectRation)
    {
        parent::__construct(sprintf(self::MESSAGE_TEMPLATE, $aspectRation));
    }
}
