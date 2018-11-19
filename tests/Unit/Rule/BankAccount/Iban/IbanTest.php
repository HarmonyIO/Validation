<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\BankAccount\Iban;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\BankAccount\Iban\Iban;

class IbanTest extends TestCase
{
    /** @var Iban */
    private $iban;

    // phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->iban = new class() extends Iban
        {
            public function test(string $ibanCode): bool
            {
                return $this->validateChecksum($ibanCode);
            }
        };
    }

    /**
     * @dataProvider provideInvalidIbanCodes
     */
    public function testValidateChecksumReturnsFalseOnInvalidChecksum(string $ibanCode): void
    {
        $this->assertFalse($this->iban->test($ibanCode));
    }

    /**
     * @dataProvider provideValidIbanCodes
     */
    public function testValidateChecksumReturnsTrueOnValidChecksum(string $ibanCode): void
    {
        $this->assertTrue($this->iban->test($ibanCode));
    }

    /**
     * @return string[]
     */
    public function provideInvalidIbanCodes(): array
    {
        return [
            ['AL901490122010010999234354354'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidIbanCodes(): array
    {
        return [
            ['AL47212110090000000235698741'],
        ];
    }
}
