<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\VideoService\YouTube;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\VideoService\YouTube\VideoId;
use PHPUnit\Framework\MockObject\MockObject;

class VideoIdTest extends TestCase
{
    /** @var MockObject|Client */
    private $httpClient;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);
    }

    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new VideoId($this->httpClient));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new VideoId($this->httpClient))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new VideoId($this->httpClient))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new VideoId($this->httpClient))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new VideoId($this->httpClient))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new VideoId($this->httpClient))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new VideoId($this->httpClient))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new VideoId($this->httpClient))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new VideoId($this->httpClient))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsTrueForValidId(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['type' => 'video']))
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(200)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertTrue((new VideoId($this->httpClient))->validate('youtubeId'));
    }

    public function testValidateReturnsFalseOnNon200Response(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(400)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertFalse((new VideoId($this->httpClient))->validate('youtubeId'));
    }

    public function testValidateReturnsFalseForNonJsonResponse(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn('notJson')
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(200)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertFalse((new VideoId($this->httpClient))->validate('youtubeId'));
    }

    public function testValidateReturnsFalseWhenJsonResponseDoesNotContainTypeKey(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['notType' => 'video']))
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(200)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertFalse((new VideoId($this->httpClient))->validate('youtubeId'));
    }

    public function testValidateReturnsFalseWhenTypeIsNotVideo(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['type' => 'notVideo']))
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(200)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertFalse((new VideoId($this->httpClient))->validate('youtubeId'));
    }
}
