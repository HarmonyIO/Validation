<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type\Svg;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Enum\File\Image\Svg\Element;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;

class ValidElements implements Rule
{
    /** @var Element */
    private $element;

    public function __construct()
    {
        $this->element = new Element();
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

                /** @var \DOMElement $node */
                foreach ($domDocument->getElementsByTagName('*') as $node) {
                    if (!$this->element->exists($node->nodeName)) {
                        return false;
                    }
                }

                libxml_use_internal_errors($useInternalErrors);

                return true;
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
