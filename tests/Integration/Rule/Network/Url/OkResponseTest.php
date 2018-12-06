<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Integration\Rule\Network\Url;

use Amp\Artax\DefaultClient;
use Amp\Redis\Client as RedisClient;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\HttpClient\Client\ArtaxClient;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Network\Url\OkResponse;

class OkResponseTest extends TestCase
{
    /** @var ArtaxClient */
    private $httpClient;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = new ArtaxClient(new DefaultClient(), new Redis(new RedisClient('tcp://localhost:6379')));
    }

    public function testValidateReturnsFalseOnNotFoundResponse(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate('http://pieterhordijk.com/dlksjksjfkhdsfjk'));
    }

    public function testValidateReturnsFalseOnNonExistingDomain(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate('http://dkhj3kry43iufhr3e.example.com'));
    }

    public function testValidateReturnsFalseWhenResponseHasErrorStatusCode(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate('https://httpbin.org/status/500'));
    }

    public function testValidateReturnsTrueOnRedirectedOkResponse(): void
    {
        $this->assertTrue((new OkResponse($this->httpClient))->validate('http://pieterhordijk.com'));
    }

    public function testValidateReturnsTrueOnOkResponse(): void
    {
        $this->assertTrue((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com'));
    }

    public function testValidateReturnsTrueOnOkResponseWithPath(): void
    {
        $this->assertTrue((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com/contact'));
    }
}
