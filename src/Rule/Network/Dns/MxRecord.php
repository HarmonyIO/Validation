<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\Dns;

use Amp\Dns\NoRecordException;
use Amp\Dns\Record;
use Amp\Promise;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use function Amp\call;
use function Amp\Dns\query;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class MxRecord implements Rule
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
                return bubbleUp($result);
            }

            try {
                yield query($value, Record::MX);

                return succeed();
            } catch (NoRecordException $e) {
                return fail(new Error('Network.Dns.MxRecord'));
            }
        });
    }
}
