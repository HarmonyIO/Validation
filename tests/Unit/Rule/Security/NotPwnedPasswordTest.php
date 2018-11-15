<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Security;

use Amp\Artax\Request;
use Amp\Artax\Response;
use Amp\ByteStream\InputStream;
use Amp\ByteStream\Message;
use Amp\Success;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Http\Client;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Security\NotPwnedPassword;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class NotPwnedPasswordTest extends TestCase
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
        $this->assertInstanceOf(Rule::class, new NotPwnedPassword($this->httpClient, 10));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate(static function (): void {
        }));
    }

    public function testValidatePassesCorrectHashToPwnedPasswordsService(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://api.pwnedpasswords.com/range/5BAA6', $request->getUri());

                /** @var MockObject|InputStream $inputStream */
                $inputStream = $this->createMock(InputStream::class);

                $inputStream
                    ->method('read')
                    ->willReturnOnConsecutiveCalls(new Success('foo'), new Success(null))
                ;

                $message = new Message($inputStream);

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn($message)
                ;

                return new Success($response);
            })
        ;

        wait((new NotPwnedPassword($this->httpClient, 10))->validate('password'));
    }

    public function testValidateReturnsFalseWhenOverThreshold(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://api.pwnedpasswords.com/range/5BAA6', $request->getUri());

                $data = "0018A45C4D1DEF81644B54AB7F969B88D65:1\r\n";
                $data .= "1E4C9B93F3F0682250B6CF8331B7EE68FD8:11\r\n";
                $data .= "011053FD0102E94D6AE2F8B83D76FAF94F6:1\r\n";

                /** @var MockObject|InputStream $inputStream */
                $inputStream = $this->createMock(InputStream::class);

                $inputStream
                    ->method('read')
                    ->willReturnOnConsecutiveCalls(new Success($data), new Success(null))
                ;

                $message = new Message($inputStream);

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn($message)
                ;

                return new Success($response);
            })
        ;

        $this->assertFalse((new NotPwnedPassword($this->httpClient, 10))->validate('password'));
    }

    public function testValidateReturnsTrueWhenExactlyThreshold(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://api.pwnedpasswords.com/range/5BAA6', $request->getUri());

                $data = "0018A45C4D1DEF81644B54AB7F969B88D65:1\r\n";
                $data .= "1E4C9B93F3F0682250B6CF8331B7EE68FD8:10\r\n";
                $data .= "011053FD0102E94D6AE2F8B83D76FAF94F6:1\r\n";

                /** @var MockObject|InputStream $inputStream */
                $inputStream = $this->createMock(InputStream::class);

                $inputStream
                    ->method('read')
                    ->willReturnOnConsecutiveCalls(new Success($data), new Success(null))
                ;

                $message = new Message($inputStream);

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn($message)
                ;

                return new Success($response);
            })
        ;

        $this->assertTrue((new NotPwnedPassword($this->httpClient, 10))->validate('password'));
    }

    public function testValidateReturnsTrueWhenBelowThreshold(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://api.pwnedpasswords.com/range/5BAA6', $request->getUri());

                $data = "0018A45C4D1DEF81644B54AB7F969B88D65:1\r\n";
                $data .= "1E4C9B93F3F0682250B6CF8331B7EE68FD8:9\r\n";
                $data .= "011053FD0102E94D6AE2F8B83D76FAF94F6:1\r\n";

                /** @var MockObject|InputStream $inputStream */
                $inputStream = $this->createMock(InputStream::class);

                $inputStream
                    ->method('read')
                    ->willReturnOnConsecutiveCalls(new Success($data), new Success(null))
                ;

                $message = new Message($inputStream);

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn($message)
                ;

                return new Success($response);
            })
        ;

        $this->assertTrue((new NotPwnedPassword($this->httpClient, 10))->validate('password'));
    }
}
