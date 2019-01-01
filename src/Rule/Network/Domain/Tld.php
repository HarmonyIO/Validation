<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\Domain;

use Amp\Promise;
use HarmonyIO\Cache\Ttl;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\CachingRequest;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

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
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            $response = yield $this->request();

            if ($this->isValidTld($response, $value)) {
                return succeed();
            }

            return fail('Network.Domain.Tld');
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
        return new CachingRequest(self::class, new Ttl(Ttl::ONE_WEEK), self::SOURCE);
    }

    private function isValidTld(string $result, string $tld): bool
    {
        $tlds = explode("\n", trim($result));

        array_shift($tlds);

        return in_array(strtoupper($tld), $tlds, true);
    }
}
