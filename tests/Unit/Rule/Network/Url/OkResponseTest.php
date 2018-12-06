<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Url;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Network\Url\OkResponse;
use HarmonyIO\Validation\Rule\Rule;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class OkResponseTest extends TestCase
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
        $this->assertInstanceOf(Rule::class, new OkResponse($this->httpClient));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new OkResponse($this->httpClient))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingAUrlWithoutProtocol(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate('pieterhordijk.com'));
    }

    public function testValidateReturnsFalseWhenPassingAUrlWithoutHost(): void
    {
        $this->assertFalse((new OkResponse($this->httpClient))->validate('https://'));
    }

    public function testValidatePassesUrlToClient(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://pieterhordijk.com', $request->getArtaxRequest()->getUri());

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn('foo')
                ;

                return new Success($response);
            })
        ;

        wait((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com'));
    }

    public function testValidateReturnsFalseWhenRequestResultsInANon200Response(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://pieterhordijk.com/foobar', $request->getArtaxRequest()->getUri());

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn('foo')
                ;

                $response
                    ->method('getNumericalStatusCode')
                    ->willReturn(404)
                ;

                return new Success($response);
            })
        ;

        $this->assertFalse((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com/foobar'));
    }

    public function testValidateReturnsTrueWhenClientReturnsOkResponse(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://pieterhordijk.com/contact', $request->getArtaxRequest()->getUri());

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn('foo')
                ;

                $response
                    ->method('getNumericalStatusCode')
                    ->willReturn(200)
                ;

                return new Success($response);
            })
        ;

        $this->assertTrue((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com/contact'));
    }
}
