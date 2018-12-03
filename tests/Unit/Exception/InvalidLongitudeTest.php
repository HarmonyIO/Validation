<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Exception;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidLongitude;

class InvalidLongitudeTest extends TestCase
{
    public function testMessageIsFormattedCorrectly(): void
    {
        $typeException = new InvalidLongitude(185);

        $this->assertSame(
            'Provided longitude (`185`) must be within range -180 to 180 (exclusive).',
            $typeException->getMessage()
        );
    }
}
