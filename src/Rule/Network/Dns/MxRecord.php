<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\Dns;

use Amp\Dns\NoRecordException;
use Amp\Dns\Record;
use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\Dns\query;

final class MxRecord implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(static function () use ($value) {
            try {
                yield query($value, Record::MX);

                return true;
            } catch (NoRecordException $e) {
                return false;
            }
        });
    }
}
