<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Integration\Rule\VideoService\YouTube;

use Amp\Artax\DefaultClient;
use Amp\Redis\Client as RedisClient;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\HttpClient\Client\ArtaxClient;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\VideoService\YouTube\VideoId;

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
     * @dataProvider provideValidYouTubeIds
     */
    public function testValidYouTubeIdsToReturnTrue(string $id): void
    {
        $this->assertTrue((new VideoId($this->httpClient))->validate($id));
    }

    /**
     * @dataProvider provideNonExistingYouTubeIds
     */
    public function testNonExistingYouTubeIdsToReturnFalse(string $id): void
    {
        $this->assertFalse((new VideoId($this->httpClient))->validate($id));
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
}
