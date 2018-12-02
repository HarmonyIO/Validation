<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Age;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class Minimum implements Rule
{
    /** @var int */
    private $minimumAge;

    public function __construct(int $minimumAge)
    {
        $this->minimumAge = $minimumAge;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!$value instanceof \DateTimeInterface) {
            return new Success(false);
        }

        $targetDateTime = (new \DateTimeImmutable())
            ->sub(new \DateInterval(sprintf('P%dY', $this->minimumAge)))
            ->setTime(0, 0, 0, 0)
        ;

        $birthDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value->format('Y-m-d 00:00:00'));

        return new Success($birthDateTime <= $targetDateTime);
    }
}
