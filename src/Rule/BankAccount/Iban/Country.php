<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\BankAccount\Iban;

use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;

abstract class Country implements Rule
{
    /** @var string */
    private $pattern;

    /** @var string */
    private $errorMessage;

    public function __construct(string $pattern, string $errorMessage)
    {
        $this->pattern      = $pattern;
        $this->errorMessage = sprintf('BankAccount.Iban.Country.%s', ucfirst($errorMessage));
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if (preg_match($this->pattern, $value, $matches) !== 1) {
                return fail(new Error($this->errorMessage));
            }

            return (new Checksum())->validate($value);
        });
    }
}
