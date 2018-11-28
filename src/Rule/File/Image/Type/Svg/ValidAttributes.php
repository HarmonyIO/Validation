<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type\Svg;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Enum\File\Image\Svg\Attribute;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;

class ValidAttributes implements Rule
{
    /** @var Attribute */
    private $attribute;

    public function __construct()
    {
        $this->attribute = new Attribute();
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
            if (!yield (new File())->validate($value)) {
                return false;
            }

            if ((yield (new MimeType('image/svg'))->validate($value)) === false) {
                return false;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                $useInternalErrors = libxml_use_internal_errors(true);

                $domDocument = new \DOMDocument();
                $domDocument->load($value);

                foreach ($domDocument->getElementsByTagName('*') as $node) {
                    foreach ($node->attributes as $attribute) {
                        if (!$this->attribute->exists($attribute->nodeName)) {
                            return false;
                        }
                    }
                }

                libxml_use_internal_errors($useInternalErrors);

                return true;
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
