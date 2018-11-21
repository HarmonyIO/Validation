<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\VideoService\YouTube;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\Validation\Rule\Rule;

class VideoUrl implements Rule
{
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

        if (!$this->validateUrlStructure($value)) {
            return new Success(false);
        }

        return (new VideoId($this->httpClient))->validate($this->getId($value));
    }

    private function validateUrlStructure(string $value): bool
    {
        // base URL format validation
        if (preg_match('~^(http(s)?://)?((w){3}.)?youtu(be\.com|\.be)/.+~', $value, $matches) !== 1) {
            return false;
        }

        // if path does not contain "watch" we don't have to check for query string variables and assume it is valid
        if (!in_array('watch', explode('/', parse_url($value, PHP_URL_PATH)), true)) {
            return true;
        }

        if (parse_url($value, PHP_URL_QUERY) === null) {
            return false;
        }

        parse_str(parse_url($value, PHP_URL_QUERY), $queryStringParameters);

        return array_key_exists('v', $queryStringParameters);
    }

    private function getId(string $value): string
    {
        if (in_array('watch', explode('/', parse_url($value, PHP_URL_PATH)), true)) {
            parse_str(parse_url($value, PHP_URL_QUERY), $queryStringParameters);

            return $queryStringParameters['v'];
        }

        return explode('/', parse_url($value, PHP_URL_PATH))[0];
    }
}
