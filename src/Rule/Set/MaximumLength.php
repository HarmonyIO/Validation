<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Set;

use Amp\Promise;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class MaximumLength implements Rule
{
    /** @var int */
    private $maximumLength;

    public function __construct(int $maximumLength)
    {
        $this->maximumLength = $maximumLength;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new Countable())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if (count($value) > $this->maximumLength) {
                return fail('Set.MaximumLength', new Parameter('length', $this->maximumLength));
            }

            return succeed();
        });
    }
}
