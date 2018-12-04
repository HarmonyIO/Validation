<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Network\Domain;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Network\Domain\Tld;
use HarmonyIO\Validation\Rule\Rule;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class TldTest extends TestCase
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
        $this->assertInstanceOf(Rule::class, new Tld($this->httpClient));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Tld($this->httpClient))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Tld($this->httpClient))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Tld($this->httpClient))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Tld($this->httpClient))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Tld($this->httpClient))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Tld($this->httpClient))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Tld($this->httpClient))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Tld($this->httpClient))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsUsesCorrectUrl(): void
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

        $this->assertFalse((new Tld($this->httpClient))->validate('foo'));
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

        $this->assertFalse((new Tld($this->httpClient))->validate(''));
    }

    public function testValidateReturnsTrueOnExactCasingMatch(): void
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

        $this->assertTrue((new Tld($this->httpClient))->validate('BAR'));
    }

    public function testValidateReturnsTrueOnLowerCasingMatch(): void
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

        $this->assertTrue((new Tld($this->httpClient))->validate('bar'));
    }
}
