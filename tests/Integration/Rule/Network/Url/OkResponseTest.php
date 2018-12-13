<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Integration\Rule\Network\Url;

use Amp\Artax\DefaultClient;
use Amp\Redis\Client as RedisClient;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\HttpClient\Client\ArtaxClient;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\Url\OkResponse;
use function Amp\Promise\wait;

class OkResponseTest extends TestCase
{
    /** @var ArtaxClient */
    private $httpClient;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = new ArtaxClient(new DefaultClient(), new Redis(new RedisClient('tcp://localhost:6379')));
    }

    public function testValidateFailsOnNotFoundResponse(): void
    {
        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('http://pieterhordijk.com/dlksjksjfkhdsfjk'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.OkResponse', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsOnNonExistingDomain(): void
    {
        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('http://dkhj3kry43iufhr3e.example.com'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.OkResponse', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenResponseHasErrorStatusCode(): void
    {
        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('https://httpbin.org/status/500'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.OkResponse', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsOnRedirectedOkResponse(): void
    {
        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('http://pieterhordijk.com'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsOnOkResponse(): void
    {
        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsOnOkResponseWithPath(): void
    {
        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com/contact'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
