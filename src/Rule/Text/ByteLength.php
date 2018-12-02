<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Text;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Rule\Rule;

final class ByteLength implements Rule
{
    /** @var int */
    private $length;

    public function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!is_string($value)) {
            return new Success(false);
        }

        return new Success(strlen($value) === $this->length);
    }
}
