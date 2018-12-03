<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

final class InvalidLongitude extends Exception
{
    private const MESSAGE = 'Provided longitude (`%s`) must be within range -180 to 180 (exclusive).';

    public function __construct(float $latitude)
    {
        parent::__construct(sprintf(self::MESSAGE, $latitude));
    }
}
