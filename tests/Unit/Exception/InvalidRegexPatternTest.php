<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Exception;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidRegexPattern;

class InvalidRegexPatternTest extends TestCase
{
    public function testMessageIsFormattedCorrectly(): void
    {
        $typeException = new InvalidRegexPattern('ThePattern');

        $this->assertSame(
            'Provided regex pattern (`ThePattern`) is not valid.',
            $typeException->getMessage()
        );
    }
}
