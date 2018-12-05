<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\Domain;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\CachingRequest;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

final class Tld implements Rule
{
    private const SOURCE = 'https://data.iana.org/TLD/tlds-alpha-by-domain.txt';

    /** @var Client */
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
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

            $result = yield $this->request();

            return $this->isValidTld($result, $value);
        });
    }

    /**
     * @return Promise<string>
     */
    private function request(): Promise
    {
        return call(function () {
            /** @var Response $response */
            $response = yield $this->httpClient->request($this->buildRequest());

            return $response->getBody();
        });
    }

    private function buildRequest(): Request
    {
        return new CachingRequest(self::class, 60*60*24*7, self::SOURCE);
    }

    private function isValidTld(string $result, string $tld): bool
    {
        $tlds = explode("\n", trim($result));

        array_shift($tlds);

        return in_array(strtoupper($tld), $tlds, true);
    }
}
