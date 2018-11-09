<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Numeric;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

class Maximum implements Rule
{
    /** @var int */
    private $maximum;

    public function __construct(int $maximum)
    {
        $this->maximum = $maximum;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_numeric($value)) {
            return new Success(false);
        }

        return new Success($value <= $this->maximum);
    }
}
