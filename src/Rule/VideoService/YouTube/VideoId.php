<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\VideoService\YouTube;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\CachingRequest;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

class VideoId implements Rule
{
    private const BASE_URL = 'https://www.youtube.com/oembed?url=%s%s&format=json';

    private const BASE_VIDEO_URL = 'https://www.youtube.com/watch?v=';

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
            $url = sprintf(self::BASE_URL, rawurlencode(self::BASE_VIDEO_URL), rawurlencode($value));

            /** @var Response $response */
            $response = yield $this->httpClient->request(new CachingRequest(self::class, 3600, $url, 'GET'));

            if ($response->getNumericalStatusCode() !== 200) {
                return false;
            }

            $result = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return false;
            }

            return array_key_exists('type', $result) && $result['type'] === 'video';
        });
    }
}
