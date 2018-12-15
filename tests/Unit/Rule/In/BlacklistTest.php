<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\In;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\In\Blacklist;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\Promise\wait;

class BlacklistTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Blacklist('item1'));
    }

    public function testValidateFailsWhenValueIsTheFirstItemInTheBlacklist(): void
    {
        /** @var Result $result */
        $result = wait((new Blacklist('item1', 'item2', 'item3'))->validate('item1'));

        $this->assertFalse($result->isValid());
        $this->assertSame('In.Blacklist', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenValueIsTheLastItemInTheBlacklist(): void
    {
        /** @var Result $result */
        $result = wait((new Blacklist('item1', 'item2', 'item3'))->validate('item3'));

        $this->assertFalse($result->isValid());
        $this->assertSame('In.Blacklist', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenValueIsNotInTheBlacklist(): void
    {
        /** @var Result $result */
        $result = wait((new Blacklist('item1'))->validate('item3'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
