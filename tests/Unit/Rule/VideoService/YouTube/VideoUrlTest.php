<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\VideoService\YouTube;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\VideoService\YouTube\VideoUrl;
use PHPUnit\Framework\MockObject\MockObject;

class VideoUrlTest extends TestCase
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
        $this->assertInstanceOf(Rule::class, new VideoUrl($this->httpClient));
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new VideoUrl($this->httpClient))->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new VideoUrl($this->httpClient))->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new VideoUrl($this->httpClient))->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new VideoUrl($this->httpClient))->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new VideoUrl($this->httpClient))->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new VideoUrl($this->httpClient))->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new VideoUrl($this->httpClient))->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new VideoUrl($this->httpClient))->validate(static function (): void {
        }));
    }

    public function testValidateReturnsFalseOnUnrecognizedUrlProtocol(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        $this->assertFalse((new VideoUrl($this->httpClient))->validate('ftp://youtube.com/watch?v=jNQXAC9IVRw'));
    }

    public function testValidateReturnsFalseOnUnrecognizedDomain(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        $this->assertFalse((new VideoUrl($this->httpClient))->validate('https://notyoutube.com/watch?v=jNQXAC9IVRw'));
    }

    public function testValidateReturnsFalseOnMissingPath(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        $this->assertFalse((new VideoUrl($this->httpClient))->validate('https://youtube.com/'));
    }

    public function testValidateReturnsTrueWhenWatchIsNotInPath(): void
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
            ->expects($this->once())
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertTrue((new VideoUrl($this->httpClient))->validate('https://youtube.com/foobar'));
    }

    public function testValidateReturnsFalseWhenWatchIsInPathButMissingAQueryString(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        $this->assertFalse((new VideoUrl($this->httpClient))->validate('https://youtube.com/watch'));
    }

    public function testValidateReturnsFalseWhenWatchIsInPathButMissingTheVQueryStringParameter(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        $this->assertFalse((new VideoUrl($this->httpClient))->validate('https://youtube.com/watch?foo=bar'));
    }

    public function testValidateReturnsTrueWhenUrlContainsBothTheWatchPathAndTheVQueryStringParameter(): void
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
            ->expects($this->once())
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertTrue((new VideoUrl($this->httpClient))->validate('https://youtube.com/watch?v=bar'));
    }

    /**
     * @dataProvider provideInvalidYouTubeUrls
     */
    public function testValidateReturnsFalseForInvalidYouTubeUrl(string $url): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        $this->assertFalse((new VideoUrl($this->httpClient))->validate($url));
    }

    /**
     * @dataProvider provideValidYouTubeUrls
     */
    public function testValidateReturnsTrueForValidYouTubeUrl(string $url): void
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
            ->expects($this->once())
            ->method('request')
            ->willReturn(new Success($response))
        ;

        $this->assertTrue((new VideoUrl($this->httpClient))->validate($url));
    }

    /**
     * @return string[]
     */
    public function provideInvalidYouTubeUrls(): array
    {
        return [
            ['http://youtube.be.com/watch?v=jNQXAC9IVRw'],
            ['https://youtubecom/watch?v=jNQXAC9IVRw'],
            ['https://youtubebe/watch?v=jNQXAC9IVRw'],
            ['https://example.com/watch?v=jNQXAC9IVRw'],
            ['http://youtube.com/watch?iv=jNQXAC9IVRw'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidYouTubeUrls(): array
    {
        return [
            ['http://youtube.com/watch?v=jNQXAC9IVRw'],
            ['http://www.youtube.com/watch?v=jNQXAC9IVRw&feature=related'],
            ['https://youtube.com/jNQXAC9IVRw'],
            ['http://youtu.be/jNQXAC9IVRw'],
            ['youtube.com/jNQXAC9IVRw'],
            ['youtube.com/jNQXAC9IVRw'],
            ['http://www.youtube.com/embed/watch?feature=player_embedded&v=jNQXAC9IVRw'],
            ['http://www.youtube.com/watch?v=jNQXAC9IVRw'],
            ['http://youtu.be/jNQXAC9IVRw'],
        ];
    }
}
