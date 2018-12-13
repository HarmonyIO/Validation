<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Url;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\Url\Url;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class UrlTest extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Url::class);
    }

    public function testValidateFailsWhenPassingAUrlWithoutProtocol(): void
    {
        /** @var Result $result */
        $result = wait((new Url())->validate('pieterhordijk.com'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.Url', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAUrlWithoutHost(): void
    {
        /** @var Result $result */
        $result = wait((new Url())->validate('https://'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.Url', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenPassingAValidUrl(): void
    {
        /** @var Result $result */
        $result = wait((new Url())->validate('https://pieterhordijk.com'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenPassingAValidUrlWithPort(): void
    {
        /** @var Result $result */
        $result = wait((new Url())->validate('https://pieterhordijk.com:1337'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
