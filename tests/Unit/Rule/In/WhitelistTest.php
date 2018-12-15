<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\In;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\In\Whitelist;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\Promise\wait;

class WhitelistTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Whitelist('item1'));
    }

    public function testValidateFailsWhenValueIsNotInTheWhitelist(): void
    {
        /** @var Result $result */
        $result = wait((new Whitelist('item1'))->validate('item3'));

        $this->assertFalse($result->isValid());
        $this->assertSame('In.Whitelist', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenValueIsTheFirstItemInTheWhitelist(): void
    {
        /** @var Result $result */
        $result = wait((new Whitelist('item1', 'item2', 'item3'))->validate('item1'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenValueIsTheLastItemInTheWhitelist(): void
    {
        /** @var Result $result */
        $result = wait((new Whitelist('item1', 'item2', 'item3'))->validate('item3'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
