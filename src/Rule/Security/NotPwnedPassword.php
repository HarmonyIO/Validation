<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Security;

use Amp\Promise;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\CachingRequest;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class NotPwnedPassword implements Rule
{
    private const BASE_URL = 'https://api.pwnedpasswords.com/range/%s';

    private const REQUEST_ACCEPT_HEADER = 'application/vnd.haveibeenpwned.v2+json';

    /** @var Client */
    private $httpClient;

    /** @var int */
    private $threshold;

    public function __construct(Client $httpClient, int $threshold)
    {
        $this->httpClient = $httpClient;
        $this->threshold  = $threshold;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            $response = yield $this->request($value);

            if ($this->getNumberOfHits($response, $value) > $this->threshold) {
                return fail(new Error('Security.NotPwnedPassword', new Parameter('threshold', $this->threshold)));
            }

            return succeed();
        });
    }

    /**
     * @return Promise<string>
     */
    private function request(string $password): Promise
    {
        return call(function () use ($password) {
            /** @var Response $response */
            $response = yield $this->httpClient->request($this->buildRequest($password));

            return $response->getBody();
        });
    }

    private function buildRequest(string $password): Request
    {
        return (new CachingRequest(self::class, 60*60*24, $this->buildUrl($password)))
            ->addHeader('Accept', self::REQUEST_ACCEPT_HEADER)
        ;
    }

    private function buildUrl(string $password): string
    {
        $hashedValue = $this->getHash($password);

        return sprintf(self::BASE_URL, substr($hashedValue, 0, 5));
    }

    private function getHash(string $password): string
    {
        return strtoupper(sha1($password));
    }

    private function getNumberOfHits(string $result, string $password): int
    {
        $hash  = substr($this->getHash($password), 5);
        $lines = explode("\r\n", $result);

        foreach ($lines as $line) {
            if (strpos($line, $hash) !== 0) {
                continue;
            }

            $hit = explode(':', $line);

            return (int) trim($hit[1]);
        }

        return 0;
    }
}
