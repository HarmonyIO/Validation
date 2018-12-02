<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\Dns;

use Amp\Dns\NoRecordException;
use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Enum\Network\Dns\RecordType;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\Dns\query;

final class RecordExists implements Rule
{
    /** @var RecordType */
    private $recordType;

    public function __construct(RecordType $recordType)
    {
        $this->recordType = $recordType;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(function () use ($value) {
            try {
                yield query($value, $this->recordType->getValue());

                return true;
            } catch (NoRecordException $e) {
                return false;
            }
        });
    }
}
