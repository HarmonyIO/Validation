<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Integration\Rule\VideoService\YouTube;

use Amp\Artax\DefaultClient;
use Amp\Redis\Client as RedisClient;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\HttpClient\Client\ArtaxClient;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\VideoService\YouTube\VideoId;
use function Amp\Promise\wait;

class VideoIdTest extends TestCase
{
    /** @var ArtaxClient */
    private $httpClient;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = new ArtaxClient(new DefaultClient(), new Redis(new RedisClient('tcp://localhost:6379')));
    }

    /**
     * @dataProvider provideNonExistingYouTubeIds
     */
    public function testValidateFailsOnNonExistingYouTubeId(string $id): void
    {
        /** @var Result $result */
        $result = wait((new VideoId($this->httpClient))->validate($id));

        $this->assertFalse($result->isValid());
        $this->assertSame('VideoService.YouTube.VideoId', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidYouTubeIds
     */
    public function testValidateSucceedsOnValidYouTubeId(string $id): void
    {
        /** @var Result $result */
        $result = wait((new VideoId($this->httpClient))->validate($id));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    /**
     * @return string[]
     */
    public function provideNonExistingYouTubeIds(): array
    {
        return [
            ['bgUHF-N0XhM'],
            ['xxxxxxxxxxx'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidYouTubeIds(): array
    {
        return [
            ['e64Ks2YoJuc'],
            ['jNQXAC9IVRw'],
            ['bgUHF_N0XhM'],
            ['TlqKFlU7YAs'],
            ['J3UyjlaBMcY'],
            ['-FIHqoTcZog'],
        ];
    }
}
