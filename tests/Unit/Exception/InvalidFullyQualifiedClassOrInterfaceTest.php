<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Exception;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidFullyQualifiedClassOrInterfaceName;

class InvalidFullyQualifiedClassOrInterfaceTest extends TestCase
{
    public function testMessageIsFormattedCorrectly(): void
    {
        $typeException = new InvalidFullyQualifiedClassOrInterfaceName('Foo\\Bar');

        $this->assertSame(
            'Expected type `Foo\\Bar` should be a valid fully qualified class or interface name.',
            $typeException->getMessage()
        );
    }

    public function testTheConstructorPassesTheCode(): void
    {
        $typeException = new InvalidFullyQualifiedClassOrInterfaceName('Foo\\Bar', 10);

        $this->assertSame(10, $typeException->getCode());
    }

    public function testTheConstructorPassesThePreviousException(): void
    {
        $previousException = new \Exception();

        $typeException = new InvalidFullyQualifiedClassOrInterfaceName('Foo\\Bar', 0, $previousException);

        $this->assertSame($previousException, $typeException->getPrevious());
    }
}
