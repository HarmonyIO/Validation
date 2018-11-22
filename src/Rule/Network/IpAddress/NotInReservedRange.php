<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\IpAddress;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

class NotInReservedRange implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(static function () use ($value) {
            $inCidrRangeRule = new InCidrRange(
                '100.64.0.0/10',
                '172.16.0.0/12',
                '198.51.100.0/24',
                '203.0.113.0/24',
                '224.0.0.0/4',
                '100::/64',
                '2001:db8::/32',
                'fc00::/7',
                'ff00::/8'
            );

            // phpcs:ignore SlevomatCodingStandard.PHP.UselessParentheses.UselessParentheses
            if ((yield $inCidrRangeRule->validate($value)) === true) {
                return new Success(false);
            }

            return new Success(filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) !== false);
        });
    }
}
