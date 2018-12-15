<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Set;

use Amp\Promise;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class MinimumLength implements Rule
{
    /** @var int */
    private $minimumLength;

    public function __construct(int $minimumLength)
    {
        $this->minimumLength = $minimumLength;
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

            if (count($value) < $this->minimumLength) {
                return fail('Set.MinimumLength', new Parameter('length', $this->minimumLength));
            }

            return succeed();
        });
    }
}
