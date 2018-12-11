<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Type;

use Amp\Promise;
use HarmonyIO\Validation\Exception\InvalidFullyQualifiedClassOrInterfaceName;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Rule\Rule;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class InstanceOfType implements Rule
{
    /** @var string */
    private $fullyQualifiedClassOrInterfaceName;

    public function __construct(string $fullyQualifiedClassOrInterfaceName)
    {
        if (!class_exists($fullyQualifiedClassOrInterfaceName)
            && !interface_exists($fullyQualifiedClassOrInterfaceName)
        ) {
            throw new InvalidFullyQualifiedClassOrInterfaceName($fullyQualifiedClassOrInterfaceName);
        }

        $this->fullyQualifiedClassOrInterfaceName = $fullyQualifiedClassOrInterfaceName;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (is_object($value) && is_a($value, $this->fullyQualifiedClassOrInterfaceName)) {
            return succeed();
        }

        return fail(new Error('Type.InstanceOfType', new Parameter('type', $this->getType($value))));
    }

    /**
     * @param mixed $value
     */
    private function getType($value): string
    {
        if (!is_object($value)) {
            return gettype($value);
        }

        return get_class($value);
    }
}
