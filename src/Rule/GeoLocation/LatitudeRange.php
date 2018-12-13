<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\GeoLocation;

use Amp\Promise;
use HarmonyIO\Validation\Exception\InvalidLatitude;
use HarmonyIO\Validation\Exception\InvalidNumericalRange;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\Range;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;

class LatitudeRange implements Rule
{
    /** @var int */
    private $minimumLatitude;

    /** @var int */
    private $maximumLatitude;

    public function __construct(float $minimumLatitude, float $maximumLatitude)
    {
        if ($minimumLatitude > $maximumLatitude) {
            throw new InvalidNumericalRange($minimumLatitude, $maximumLatitude);
        }

        if (!$this->isLatitudeValid($minimumLatitude)) {
            throw new InvalidLatitude($minimumLatitude);
        }

        if (!$this->isLatitudeValid($maximumLatitude)) {
            throw new InvalidLatitude($maximumLatitude);
        }

        $this->minimumLatitude = $minimumLatitude;
        $this->maximumLatitude = $maximumLatitude;
    }

    private function isLatitudeValid(float $latitude): bool
    {
        return $latitude > -90 && $latitude < 90;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new Latitude())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            return (new Range($this->minimumLatitude, $this->maximumLatitude))->validate($value);
        });
    }
}
