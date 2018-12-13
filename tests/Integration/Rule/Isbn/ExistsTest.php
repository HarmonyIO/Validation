<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Integration\Rule\Isbn;

use Amp\Artax\DefaultClient;
use Amp\Redis\Client as RedisClient;
use HarmonyIO\Cache\Provider\Redis;
use HarmonyIO\HttpClient\Client\ArtaxClient;
use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Isbn\Exists;
use function Amp\Promise\wait;

class ExistsTest extends TestCase
{
    /** @var ArtaxClient */
    private $httpClient;

    //phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    public function setUp()
    {
        if (!isset($_ENV['BOOKS_API_KEY'])) {
            $this->markTestSkipped('Test skipped because the `BOOKS_API_KEY` google books API key is missing.');
        }

        $this->httpClient = new ArtaxClient(new DefaultClient(), new Redis(new RedisClient('tcp://localhost:6379')));
    }

    public function testValidateFailsWhenIsbnDoesNotExist(): void
    {
        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, $_ENV['BOOKS_API_KEY']))->validate('3999215003'));

        $this->assertFalse($result->isValid());
        $this->assertSame('Isbn.Exists', $result->getFirstError()->getMessage());
    }

    public function testValidateSucceedsWhenIsbnExists(): void
    {
        /** @var Result $result */
        $result = wait((new Exists($this->httpClient, $_ENV['BOOKS_API_KEY']))->validate('9788970137506'));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }
}
