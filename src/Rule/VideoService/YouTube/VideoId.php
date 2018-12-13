<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\VideoService\YouTube;

use Amp\Promise;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\CachingRequest;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class VideoId implements Rule
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
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            $url = sprintf(self::BASE_URL, rawurlencode(self::BASE_VIDEO_URL), rawurlencode($value));

            /** @var Response $response */
            $response = yield $this->httpClient->request(new CachingRequest(self::class, 3600, $url, 'GET'));

            if ($response->getNumericalStatusCode() !== 200) {
                return fail('VideoService.YouTube.VideoId');
            }

            $result = json_decode($response->getBody(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return fail('VideoService.YouTube.VideoId');
            }

            if (array_key_exists('type', $result) && $result['type'] === 'video') {
                return succeed();
            }

            return fail('VideoService.YouTube.VideoId');
        });
    }
}
