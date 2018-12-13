<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Age;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Age\Exact;
use HarmonyIO\ValidationTest\Unit\Rule\DateTimeTestCase;
use function Amp\Promise\wait;

class ExactTest extends DateTimeTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Exact::class, 18);
    }

    public function testValidateFailsWhenAgeIsLessThanRequiredAge(): void
    {
        /** @var Result $result */
        $result = wait((new Exact(18))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P1Y'))->add(new \DateInterval('P1D'))
        ));

        $this->assertFalse($result->isValid());
        $this->assertSame('Age.Minimum', $result->getErrors()[0]->getMessage());
        $this->assertSame('age', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame(18, $result->getErrors()[0]->getParameters()[0]->getValue());
    }

    public function testValidateFailsWhenAgeIsMoreThanRequiredAge(): void
    {
        /** @var Result $result */
        $result = wait((new Exact(18))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P18Y1D'))
        ));

        $this->assertFalse($result->isValid());
        $this->assertSame('Age.Maximum', $result->getErrors()[0]->getMessage());
        $this->assertSame('age', $result->getErrors()[0]->getParameters()[0]->getKey());
        $this->assertSame(18, $result->getErrors()[0]->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenAgeIsExactlyRequiredAge(): void
    {
        /** @var Result $result */
        $result = wait((new Exact(18))->validate(
            // phpcs:ignore PSR2.Methods.FunctionCallSignature.Indent,PSR2.Methods.FunctionCallSignature.CloseBracketLine
            (new \DateTimeImmutable())->sub(new \DateInterval('P18Y'))
        ));

        $this->assertTrue($result->isValid());
        $this->assertCount(0, $result->getErrors());
    }
}
