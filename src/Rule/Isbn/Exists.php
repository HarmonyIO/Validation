<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Isbn;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\CachingRequest;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

class Exists implements Rule
{
    private const API_URL = 'https://www.googleapis.com/books/v1/volumes?key=%s&q=%s';

    private const API_IDENTIFIER = 'isbn:%s';

    /** @var Client */
    private $httpClient;

    /** @var string */
    private $apiKey;

    public function __construct(Client $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey     = $apiKey;
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
            if (!yield (new Isbn())->validate($value)) {
                return false;
            }

            $url = sprintf(self::API_URL, $this->apiKey, rawurlencode(sprintf(self::API_IDENTIFIER, $value)));

            /** @var Response $response */
            $response = yield $this->httpClient->request(
                new CachingRequest(self::class, 60*60*24*7, $url, 'GET')
            );

            if ($response->getNumericalStatusCode() !== 200) {
                return false;
            }

            $result = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return false;
            }

            return $result['totalItems'] > 0;
        });
    }
}
