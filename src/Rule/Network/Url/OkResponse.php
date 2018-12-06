<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\Url;

use Amp\Artax\DnsException;
use Amp\Promise;
use Amp\Success;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

final class OkResponse implements Rule
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

        return call(function () use ($value) {
            if (!yield (new Url())->validate($value)) {
                return false;
            }

            try {
                /** @var Response $response */
                $response = yield $this->httpClient->request(new Request($value));
            } catch (DnsException $e) {
                return false;
            }

            return $response->getNumericalStatusCode() === 200;
        });
    }
}
