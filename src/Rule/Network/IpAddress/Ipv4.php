<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\IpAddress;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class Ipv4 implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success(filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false);
    }
}
