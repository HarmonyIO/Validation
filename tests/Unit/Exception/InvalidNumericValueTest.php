<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Exception;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidNumericValue;

class InvalidNumericValueTest extends TestCase
{
    public function testMessageIsFormattedCorrectly(): void
    {
        $typeException = new InvalidNumericValue('randomstring');

        $this->assertSame(
            'Value (`randomstring`) must be a numeric value.',
            $typeException->getMessage()
        );
    }
}
