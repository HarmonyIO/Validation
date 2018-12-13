<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\VideoService\YouTube;

use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\VideoService\YouTube\VideoId;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use function Amp\Promise\wait;

class VideoIdTest extends StringTestCase
{
    /** @var MockObject|Client */
    private $httpClient;

    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::__construct($name, $data, $dataName, VideoId::class, $this->httpClient);
    }

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = $this->createMock(Client::class);

        parent::setUp();
    }

    public function testValidateFailsOnNon200Response(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getNumericalStatusCode')
            ->willReturn(400)
        ;

        $this->httpClient
            ->method('request')
            ->willReturn(new Success($response))
        ;

        /** @var Result $result */
        $result = wait((new VideoId($this->httpClient))->validate('youtubeId'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoId', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsForNonJsonResponse(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn('notJson')
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
        $result = wait((new VideoId($this->httpClient))->validate('youtubeId'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoId', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenJsonResponseDoesNotContainTypeKey(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['notType' => 'video']))
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
        $result = wait((new VideoId($this->httpClient))->validate('youtubeId'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoId', $result->getFirstError()->getMessage());
    }

    public function testValidateFailsWhenTypeIsNotVideo(): void
    {
        $response = $this->createMock(Response::class);

        $response
            ->method('getBody')
            ->willReturn(json_encode(['type' => 'notVideo']))
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
        $result = wait((new VideoId($this->httpClient))->validate('youtubeId'));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoId', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsForValidId(): void
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
            ->method('request')
            ->willReturn(new Success($response))
        ;

        /** @var Result $result */
        $result = wait((new VideoId($this->httpClient))->validate('youtubeId'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
