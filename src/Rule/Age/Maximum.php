<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Age;

use Amp\Promise;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\InstanceOfType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Maximum implements Rule
{
    /** @var int */
    private $maximumAge;

    public function __construct(int $maximumAge)
    {
        $this->maximumAge = $maximumAge;
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
                return $result;
            }

            $targetDateTime = (new \DateTimeImmutable())
                ->sub(new \DateInterval(sprintf('P%dY', $this->maximumAge)))
                ->setTime(0, 0, 0, 0)
            ;

            $birthDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value->format('Y-m-d 00:00:00'));

            if ($birthDateTime >= $targetDateTime) {
                return succeed();
            }

            return fail('Age.Maximum', new Parameter('age', $this->maximumAge));
        });
    }
}
