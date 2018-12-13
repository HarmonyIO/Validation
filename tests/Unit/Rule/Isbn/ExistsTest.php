<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Isbn;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Isbn\Exists;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class ExistsTest extends StringTestCase
{
    /** @var MockObject|Client */
    private $httpClient;

    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::__construct($name, $data, $dataName, Exists::class, $this->httpClient, '12345');
    }

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::setUp();
    }

    public function testValidateFailsWhenPassingInAnInvalidIsbn10(): void
    {
        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, '12345'))->validate('0345391803'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenPassingInAnInvalidIsbn13(): void
    {
        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, '12345'))->validate('9788970137507'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Isbn', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenServiceReturnsANon200Response(): void
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

        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, '12345'))->validate('9788970137506'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Exists', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenServiceReturnsInvalidJson(): void
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

        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, '12345'))->validate('9788970137506'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Exists', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenIsbnDoesNotExist(): void
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

        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, '12345'))->validate('9788970137506'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Exists', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenIsbnDoesExist(): void
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

        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, '12345'))->validate('9788970137506'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenMultipleBooksWithIsbnExist(): void
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

        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, '12345'))->validate('9788970137506'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
