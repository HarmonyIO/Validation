<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Security;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\CachingRequest;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

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
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(function () use ($value) {
            $result = yield $this->request($value);

            if ($this->getNumberOfHits($result, $value) > $this->threshold) {
                return new Success(false);
            }

            return new Success(true);
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
