<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type\Svg;

use Amp\Promise;
use HarmonyIO\Validation\Enum\File\Image\Svg\Element;
use HarmonyIO\Validation\Exception\InvalidXml;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Xml\SafeParser;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\bubbleUp;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class ValidElements implements Rule
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
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new MimeType('image/svg'))->validate($value);

            if (!$result->isValid()) {
                return bubbleUp($result);
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                try {
                    $xmlParser = new SafeParser(file_get_contents($value));
                } catch (InvalidXml $e) {
                    return fail(new Error('File.Image.Type.Svg.ValidElements'));
                }

                /** @var \DOMElement $node */
                foreach ($xmlParser->getElementsByTagName('*') as $node) {
                    if (!$this->element->exists($node->nodeName)) {
                        return fail(new Error('File.Image.Type.Svg.ValidElements'));
                    }
                }

                return succeed();
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
