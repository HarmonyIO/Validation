<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\VideoService\YouTube;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\VideoService\YouTube\VideoUrl;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class VideoUrlTest extends StringTestCase
{
    /** @var MockObject|Client */
    private $httpClient;

    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::__construct($name, $data, $dataName, VideoUrl::class, $this->httpClient);
    }

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::setUp();
    }

    public function testValidateFailsOnUnrecognizedUrlProtocol(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate('ftp://youtube.com/watch?v=jNQXAC9IVRw'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoUrl', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsOnUnrecognizedDomain(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate('https://notyoutube.com/watch?v=jNQXAC9IVRw'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoUrl', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsOnMissingPath(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate('https://youtube.com/'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoUrl', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenWatchIsInPathButMissingAQueryString(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate('https://youtube.com/watch'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoUrl', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenWatchIsInPathButMissingTheVQueryStringParameter(): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate('https://youtube.com/watch?foo=bar'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoUrl', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideInvalidYouTubeUrls
     */
    public function testValidateFailsForInvalidYouTubeUrl(string $url): void
    {
        $this->httpClient
            ->expects($this->never())
            ->method('request')
        ;

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate($url));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoUrl', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenWatchIsNotInPath(): void
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

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate('https://youtube.com/foobar'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    public function testValidateSucceedsWhenUrlContainsBothTheWatchPathAndTheVQueryStringParameter(): void
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

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate('https://youtube.com/watch?v=bar'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    /**
     * @dataProvider provideValidYouTubeUrls
     */
    public function testValidateSucceedsForValidYouTubeUrl(string $url): void
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

        /** @var Result $result */
        $result = wait((new VideoUrl($this->httpClient))->validate($url));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
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
