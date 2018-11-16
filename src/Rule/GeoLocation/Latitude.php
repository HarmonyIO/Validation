<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\GeoLocation;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class Latitude implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_numeric($value)) {
            return new Success(false);
        }

        return new Success($value > -90 && $value < 90);
    }
}
