<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\IpAddress;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Ipv6 implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
                return succeed();
            }

            return fail(new Error('Network.IpAddress.Ipv6'));
        });
    }
}
