<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Integration\Rule\Network\Domain;

use Amp\Artax\DefaultClient;
use Amp\Redis\Client as RedisClient;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\HttpClient\Client\ArtaxClient;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Network\Domain\Tld;

class TldTest extends TestCase
{
    /** @var ArtaxClient */
    private $httpClient;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        $this->httpClient = new ArtaxClient(new DefaultClient(), new Redis(new RedisClient('tcp://localhost:6379')));
    }

    /**
     * @dataProvider provideValidTlds
     */
    public function testValidYouTubeIdsToReturnTrue(string $tld): void
    {
        $this->assertTrue((new Tld($this->httpClient))->validate($tld));
    }

    /**
     * @dataProvider provideInvalidTlds
     */
    public function testNonExistingYouTubeIdsToReturnFalse(string $tld): void
    {
        $this->assertFalse((new Tld($this->httpClient))->validate($tld));
    }

    /**
     * @return string[]
     */
    public function provideValidTlds(): array
    {
        return [
            ['YOUTUBE'],
            ['youtube'],
            ['XN--TIQ49XQYJ'],
            ['xn--tiq49xqyj'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidTlds(): array
    {
        return [
            ['SHIRLEYTHISWILLNEVERBEAVALIDTLDRIGHTRIIITE'],
        ];
    }
}
