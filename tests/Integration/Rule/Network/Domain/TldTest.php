<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Integration\Rule\Network\Domain;

use Amp\Artax\DefaultClient;
use Amp\Redis\Client as RedisClient;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\HttpClient\Client\ArtaxClient;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Network\Domain\Tld;
use function Amp\Promise\wait;

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
     * @dataProvider provideInvalidTlds
     */
    public function testNonExistingYouTubeIdsToReturnFalse(string $tld): void
    {
        /** @var Result $result */
        $result = wait((new Tld($this->httpClient))->validate($tld));

        $this->assertFalse($result->isValid());
        $this->assertSame('Network.Domain.Tld', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidTlds
     */
    public function testValidYouTubeIdsToReturnTrue(string $tld): void
    {
        /** @var Result $result */
        $result = wait((new Tld($this->httpClient))->validate($tld));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
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
}
