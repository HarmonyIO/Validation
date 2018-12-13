<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Network\IpAddress;

use Amp\Promise;
use HarmonyIO\Validation\Exception\InvalidCidrRange;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Type\StringType;
use Wikimedia\IPSet;
use function Amp\call;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class InCidrRange implements Rule
{
    /** @var IPSet */
    private $cidrRanges;

    public function __construct(string ...$cidrRanges)
    {
        $currentErrorHandler = set_error_handler(static function (int $errorNumber, string $errorMessage) {
            if (strpos($errorMessage, 'IPSet') !== 0) {
                return false;
            }

            throw new InvalidCidrRange($errorMessage, $errorNumber);
        }, E_WARNING | E_USER_WARNING);

        $this->cidrRanges = new IPSet($cidrRanges);

        set_error_handler($currentErrorHandler);
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

            if ($this->cidrRanges->match($value)) {
                return succeed();
            }

            return fail(new Error('Network.IpAddress.InCidrRange'));
        });
    }
}
