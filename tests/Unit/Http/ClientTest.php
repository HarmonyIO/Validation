<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Http;

use Amp\Artax\Client as ArtaxClient;
use Amp\Artax\HttpException;
use Amp\Artax\Request;
use Amp\Artax\Response;
use Amp\Success;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\RequestFailed;
use HarmonyIO\Validation\Http\Client;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class ClientTest extends TestCase
{
    /** @var MockObject|ArtaxClient */
    private $artaxClient;

    /** @var Request */
    private $request;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->artaxClient = $this->createMock(ArtaxClient::class);
        $this->request     = new Request('http://example.com');
    }

    public function testRequestSetsDefaultUserAgent(): void
    {
        $this->artaxClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertTrue($request->hasHeader('User-Agent'));
                $this->assertSame(
                    'HarmonyIO/Validation https://github.com/HarmonyIO/Validation',
                    $request->getHeader('User-Agent')
                );

                return new Success();
            })
        ;

        wait((new Client($this->artaxClient))->request($this->request));
    }

    public function testRequestUsesPassedInUserAgentWhenSet(): void
    {
        $this->request = $this->request
            ->withHeader('User-Agent', 'Custom User Agent')
        ;

        $this->artaxClient
            ->method('request')
            ->willReturnCallback(function (Request $request) {
                $this->assertTrue($request->hasHeader('User-Agent'));
                $this->assertSame(
                    'Custom User Agent',
                    $request->getHeader('User-Agent')
                );

                return new Success();
            })
        ;

        wait((new Client($this->artaxClient))->request($this->request));
    }

    public function testRequestThrowsExceptionOnFailures(): void
    {
        $this->artaxClient
            ->method('request')
            ->willThrowException(new HttpException())
        ;

        $this->expectException(RequestFailed::class);

        wait((new Client($this->artaxClient))->request($this->request));
    }

    public function testRequestReturnsResponse(): void
    {
        $response = $this->createMock(Response::class);

        $this->artaxClient
            ->method('request')
            ->willReturn($response)
        ;

        $this->expectException(RequestFailed::class);

        $this->assertSame($response, (new Client($this->artaxClient))->request($this->request));
    }
}
