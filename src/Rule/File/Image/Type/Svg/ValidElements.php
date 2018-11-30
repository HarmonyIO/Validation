<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type\Svg;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Enum\File\Image\Svg\Element;
use HarmonyIO\Validation\Exception\InvalidXml;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Xml\SafeParser;
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
                try {
                    $xmlParser = new SafeParser(file_get_contents($value));
                } catch (InvalidXml $e) {
                    return false;
                }

                /** @var \DOMElement $node */
                foreach ($xmlParser->getElementsByTagName('*') as $node) {
                    if (!$this->element->exists($node->nodeName)) {
                        return false;
                    }
                }

                return true;
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
