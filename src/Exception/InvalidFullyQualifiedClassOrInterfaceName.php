<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Exception;

final class InvalidFullyQualifiedClassOrInterfaceName extends Exception
{
    private const MESSAGE_TEMPLATE = 'Expected type `%s` should be a valid fully qualified class or interface name.';

    public function __construct(string $fullyQualifiedClassOrInterfaceName, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf(self::MESSAGE_TEMPLATE, $fullyQualifiedClassOrInterfaceName), $code, $previous);
    }
}
