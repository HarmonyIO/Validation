<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Url;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Network\Url\Url;
use HarmonyIO\Validation\Rule\Rule;

class UrlTest extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Url());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Url())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Url())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Url())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Url())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Url())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Url())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Url())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Url())->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAUrlWithoutProtocol(): void
    {
        $this->assertFalse((new Url())->validate('pieterhordijk.com'));
    }

    public function testValidateReturnsFalseWhenPassingAUrlWithoutHost(): void
    {
        $this->assertFalse((new Url())->validate('https://'));
    }

    public function testValidateReturnsTrueWhenPassingAValidUrl(): void
    {
        $this->assertTrue((new Url())->validate('https://pieterhordijk.com'));
    }

    public function testValidateReturnsTrueWhenPassingAValidUrlWithPort(): void
    {
        $this->assertTrue((new Url())->validate('https://pieterhordijk.com:1337'));
    }
}
