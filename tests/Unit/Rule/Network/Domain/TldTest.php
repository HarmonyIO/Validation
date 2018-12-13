<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Domain;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\Domain\Tld;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class TldTest extends StringTestCase
{
    /** @var MockObject|Client */
    private $httpClient;

    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::__construct($name, $data, $dataName, Tld::class, $this->httpClient);
    }

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::setUp();
    }

    public function testValidateUsesCorrectUrl(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame(
                    'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
                    $request->getArtaxRequest()->getUri()
                );

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn("FOO\nBAR\n")
                ;

                return new Success($response);
            })
        ;

        wait((new Tld($this->httpClient))->validate('tld'));
    }

    public function testValidateStripsFirstLineFromResult(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame(
                    'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
                    $request->getArtaxRequest()->getUri()
                );

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn("FOO\nBAR\n")
                ;

                return new Success($response);
            })
        ;

        /** @var Result $result */
        $result = wait((new Tld($this->httpClient))->validate('foo'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Domain.Tld', $result->getFirstError()->getMessage());
    }

    public function testValidateStripsEmptyLastLineFromResult(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame(
                    'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
                    $request->getArtaxRequest()->getUri()
                );

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn("FOO\nBAR\n")
                ;

                return new Success($response);
            })
        ;

        /** @var Result $result */
        $result = wait((new Tld($this->httpClient))->validate(''));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Domain.Tld', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsOnExactCasingMatch(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame(
                    'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
                    $request->getArtaxRequest()->getUri()
                );

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn("FOO\nBAR\n")
                ;

                return new Success($response);
            })
        ;

        /** @var Result $result */
        $result = wait((new Tld($this->httpClient))->validate('BAR'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsOnLowerCasingMatch(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame(
                    'https://data.iana.org/TLD/tlds-alpha-by-domain.txt',
                    $request->getArtaxRequest()->getUri()
                );

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn("FOO\nBAR\n")
                ;

                return new Success($response);
            })
        ;

        /** @var Result $result */
        $result = wait((new Tld($this->httpClient))->validate('bar'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
