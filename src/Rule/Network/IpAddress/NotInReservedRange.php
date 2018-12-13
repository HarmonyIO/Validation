<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\IpAddress;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\Negate;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class NotInReservedRange implements Rule
{
    private const RESERVED_RANGES = [
        '100.64.0.0/10',
        '172.16.0.0/12',
        '198.51.100.0/24',
        '203.0.113.0/24',
        '224.0.0.0/4',
        '100::/64',
        '2001:db8::/32',
        'fc00::/7',
        'ff00::/8',
    ];

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

            /** @var Result $result */
            $result = yield (new Negate(new InCidrRange(...self::RESERVED_RANGES)))->validate($value);

            if (!$result->isValid() || filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === false) {
                return fail(new Error('Network.IpAddress.NotInReservedRange'));
            }

            return succeed();
        });
    }
}
