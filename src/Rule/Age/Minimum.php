<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Age;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\InstanceOfType;
use function Amp\call;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

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
        // prevent returning promises and instead create a custom function which takes a result *or* a promise
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new InstanceOfType(\DateTimeInterface::class))->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            $targetDateTime = (new \DateTimeImmutable())
                ->sub(new \DateInterval(sprintf('P%dY', $this->minimumAge)))
                ->setTime(0, 0, 0, 0)
            ;

            $birthDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value->format('Y-m-d 00:00:00'));

            if ($birthDateTime <= $targetDateTime) {
                return succeed();
            }

            return fail(new Error('Age.Minimum', new Parameter('minimum', $this->minimumAge)));
        });
    }
}
