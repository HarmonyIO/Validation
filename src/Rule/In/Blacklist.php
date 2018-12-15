<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\In;

use Amp\Promise;
use HarmonyIO\Validation\Rule\Rule;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class Blacklist implements Rule
{
    /** @var mixed[] */
    private $list;

    /**
     * @param mixed $item
     * @param mixed ...$list
     */
    public function __construct($item, ...$list)
    {
        $this->list = array_merge([$item], $list);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        if (!in_array($value, $this->list, true)) {
            return succeed();
        }

        return fail('In.Blacklist');
    }
}
