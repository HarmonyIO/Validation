<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Email;

use Amp\Promise;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class RfcEmailAddress implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(static function () use ($value) {
            /** @var Result $result */
            $result = yield (new StringType())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            if ((new EmailValidator())->isValid($value, new RFCValidation())) {
                return succeed();
            }

            return fail(new Error('Email.RfcEmailAddress'));
        });
    }
}
