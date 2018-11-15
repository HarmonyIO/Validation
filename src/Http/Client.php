<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Http;

use Amp\Artax\Client as ArtaxClient;
use Amp\Artax\Request;
use Amp\Promise;
use HarmonyIO\Validation\Exception\RequestFailed;

class Client
{
    private const DEFAULT_USER_AGENT_STRING = 'HarmonyIO/Validation https://github.com/HarmonyIO/Validation';

    /** @var ArtaxClient */
    private $artaxClient;

    public function __construct(ArtaxClient $artaxClient)
    {
        $this->artaxClient = $artaxClient;
    }

    public function request(Request $request): Promise
    {
        $request = $this->setUserAgentHeader($request);

        try {
            return $this->artaxClient->request($request);
        } catch (\Throwable $e) {
            throw new RequestFailed($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function setUserAgentHeader(Request $request): Request
    {
        if ($request->hasHeader('User-Agent')) {
            return $request;
        }

        return $request->withHeader('User-Agent', self::DEFAULT_USER_AGENT_STRING);
    }
}
