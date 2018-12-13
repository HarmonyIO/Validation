<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Security;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Security\NotPwnedPassword;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class NotPwnedPasswordTest extends StringTestCase
{
    /** @var MockObject|Client */
    private $httpClient;

    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::__construct($name, $data, $dataName, NotPwnedPassword::class, $this->httpClient, 10);
    }

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::setUp();
    }

    public function testValidatePassesCorrectHashToPwnedPasswordsService(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://api.pwnedpasswords.com/range/5BAA6', $request->getArtaxRequest()->getUri());

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn('foo')
                ;

                return new Success($response);
            })
        ;

        wait((new NotPwnedPassword($this->httpClient, 10))->validate('password'));
    }

    public function testValidateFailsWhenOverThreshold(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://api.pwnedpasswords.com/range/5BAA6', $request->getArtaxRequest()->getUri());

                $data = "0018A45C4D1DEF81644B54AB7F969B88D65:1\r\n";
                $data .= "1E4C9B93F3F0682250B6CF8331B7EE68FD8:11\r\n";
                $data .= "011053FD0102E94D6AE2F8B83D76FAF94F6:1\r\n";

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn($data)
                ;

                return new Success($response);
            })
        ;

        /** @var Result $result */
        $result = wait((new NotPwnedPassword($this->httpClient, 10))->validate('password'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Security.NotPwnedPassword', $result->getFirstError()->getMessage());
        $this->assertSame('threshold', $result->getFirstError()->getParameters()[0]->getKey());
        $this->assertSame(10, $result->getFirstError()->getParameters()[0]->getValue());
    }

    public function testValidateSucceedsWhenExactlyThreshold(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://api.pwnedpasswords.com/range/5BAA6', $request->getArtaxRequest()->getUri());

                $data = "0018A45C4D1DEF81644B54AB7F969B88D65:1\r\n";
                $data .= "1E4C9B93F3F0682250B6CF8331B7EE68FD8:10\r\n";
                $data .= "011053FD0102E94D6AE2F8B83D76FAF94F6:1\r\n";

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn($data)
                ;

                return new Success($response);
            })
        ;

        /** @var Result $result */
        $result = wait((new NotPwnedPassword($this->httpClient, 10))->validate('password'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateReturnsTrueWhenBelowThreshold(): void
    {
        $this->httpClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertSame('https://api.pwnedpasswords.com/range/5BAA6', $request->getArtaxRequest()->getUri());

                $data = "0018A45C4D1DEF81644B54AB7F969B88D65:1\r\n";
                $data .= "1E4C9B93F3F0682250B6CF8331B7EE68FD8:9\r\n";
                $data .= "011053FD0102E94D6AE2F8B83D76FAF94F6:1\r\n";

                $response = $this->createMock(Response::class);

                $response
                    ->method('getBody')
                    ->willReturn($data)
                ;

                return new Success($response);
            })
        ;

        /** @var Result $result */
        $result = wait((new NotPwnedPassword($this->httpClient, 10))->validate('password'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
