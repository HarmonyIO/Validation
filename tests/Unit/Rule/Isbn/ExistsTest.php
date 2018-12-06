<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Isbn;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Isbn\Exists;
use HarmonyIO\Validation\Rule\Rule;
use PHPUnit\Framework\MockObject\MockObject;

class ExistsTest extends TestCase
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
        $this->assertInstanceOf(Rule::class, new Exists($this->httpClient, '12345'));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseWhenPassingInAnInvalidIsbn10(): void
    {
            $this->assertFalse((new Exists($this->httpClient, '12345'))->validate('0345391803'));
    }

    public function testValidateReturnsFalseWhenPassingInAnInvalidIsbn13(): void
    {
        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate('9788970137507'));
    }

    public function testValidateReturnsFalseWhenServiceReturnsANon200Response(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['foo' => 'bar']))
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(500)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate('9788970137506'));
    }

    public function testValidateReturnsFalseWhenServiceReturnsInvalidJson(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn('foobar')
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(200)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate('9788970137506'));
    }

    public function testValidateReturnsFalseWhenIsbnDoesNotExist(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['totalItems' => 0]))
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(200)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertFalse((new Exists($this->httpClient, '12345'))->validate('9788970137506'));
    }

    public function testValidateReturnsTrueWhenIsbnDoesExist(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['totalItems' => 1]))
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(200)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertTrue((new Exists($this->httpClient, '12345'))->validate('9788970137506'));
    }

    public function testValidateReturnsTrueWhenMultipleBooksWithIsbnExist(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['totalItems' => 2]))
        ;

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(200)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertTrue((new Exists($this->httpClient, '12345'))->validate('9788970137506'));
    }
}
