<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\Type;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class ResourceType implements Rule
{
    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (is_resource($value)) {
            return succeed();
        }

        return fail('Type.ResourceType');
    }
}
