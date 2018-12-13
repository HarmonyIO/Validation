<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File\Image\Type\Svg;

use Amp\Promise;
use HarmonyIO\Validation\Enum\File\Image\Svg\Attribute;
use HarmonyIO\Validation\Exception\InvalidXml;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\File\MimeType;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Xml\SafeParser;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class ValidAttributes implements Rule
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
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new MimeType('image/svg'))->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                try {
                    $xmlParser = new SafeParser(file_get_contents($value));
                } catch (InvalidXml $e) {
                    return fail(new Error('File.Image.Type.Svg.ValidAttributes'));
                }

                foreach ($xmlParser->getElementsByTagName('*') as $node) {
                    foreach ($node->attributes as $attribute) {
                        if (!$this->attribute->exists($attribute->nodeName)) {
                            return fail(new Error('File.Image.Type.Svg.ValidAttributes'));
                        }
                    }
                }

                return succeed();
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
