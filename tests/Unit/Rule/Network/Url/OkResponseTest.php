<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Url;

use Amp\Artax\DnsException;
use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\Url\OkResponse;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class OkResponseTest extends StringTestCase
{
    /** @var MockObject|Client */
    private $httpClient;

    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::__construct($name, $data, $dataName, OkResponse::class, $this->httpClient);
    }

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::setUp();
    }

    public function testValidateFailsWhenPassingAUrlWithoutProtocol(): void
    {
        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('pieterhordijk.com'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.Url', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingAUrlWithoutHost(): void
    {
        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('https://'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.Url', $result->getFirstError()->getMessage());
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

    public function testValidateFailsWhenRequestResultsInANon200Response(): void
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

        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com/foobar'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.OkResponse', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenRequestResultsADnsException(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request): void {
                $this->assertSame('https://pieterhordijk.com/foobar', $request->getArtaxRequest()->getUri());

                throw new DnsException();
            })
        ;

        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com/foobar'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Url.OkResponse', $result->getFirstError()->getMessage());
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

        /** @var Result $result */
        $result = wait((new OkResponse($this->httpClient))->validate('https://pieterhordijk.com/contact'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
