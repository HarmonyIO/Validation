<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class Minimum implements Rule
{
    /** @var int */
    private $minimum;

    public function __construct(int $minimum)
    {
        $this->minimum = $minimum;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_numeric($value)) {
            return new Success(false);
        }

        return new Success($value >= $this->minimum);
    }
}
