<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Email;

use Amp\Promise;
use Amp\Success;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use HarmonyIO\Validation\Rule\Rule;

class RfcEmailAddress implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success((new EmailValidator())->isValid($value, new RFCValidation()));
    }
}
