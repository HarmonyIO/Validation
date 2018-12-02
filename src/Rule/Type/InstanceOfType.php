<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Type;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Exception\InvalidFullyQualifiedClassOrInterfaceName;
use HarmonyIO\Validation\Rule\Rule;

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
        if (!is_object($value)) {
            return new Success(false);
        }

        return new Success(is_a($value, $this->fullyQualifiedClassOrInterfaceName));
    }
}
