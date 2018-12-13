<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\Url;

use Amp\Artax\DnsException;
use Amp\Promise;
use HarmonyIO\HttpClient\Client\Client;
use HarmonyIO\HttpClient\Message\Request;
use HarmonyIO\HttpClient\Message\Response;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

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
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new Url())->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            try {
                /** @var Response $response */
                $response = yield $this->httpClient->request(new Request($value));
            } catch (DnsException $e) {
                return fail(new Error('Network.Url.OkResponse'));
            }

            if ($response->getNumericalStatusCode() === 200) {
                return succeed();
            }

            return fail(new Error('Network.Url.OkResponse'));
        });
    }
}
