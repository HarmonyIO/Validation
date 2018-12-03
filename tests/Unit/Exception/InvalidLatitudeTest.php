<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Exception;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidLatitude;

class InvalidLatitudeTest extends TestCase
{
    public function testMessageIsFormattedCorrectly(): void
    {
        $typeException = new InvalidLatitude(95);

        $this->assertSame(
            'Provided latitude (`95`) must be within range -90 to 90 (exclusive).',
            $typeException->getMessage()
        );
    }
}
