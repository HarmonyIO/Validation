<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\GeoLocation;

use Amp\Promise;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\NumericType;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Longitude implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new NumericType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if ($value > -180 && $value < 180) {
                return succeed();
            }

            return fail('GeoLocation.Longitude');
        });
    }
}
