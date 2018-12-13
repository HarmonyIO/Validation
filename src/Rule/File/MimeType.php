<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File;

use Amp\Promise;
use HarmonyIO\Validation\Exception\FileInfo;
use HarmonyIO\Validation\Result\Parameter;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;
use function HarmonyIO\Validation\fail;
use function HarmonyIO\Validation\succeed;

final class MimeType implements Rule
{
    /** @var string */
    private $mimeType;

    public function __construct(string $mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): Promise
    {
        return call(function () use ($value) {
            /** @var Result $result */
            $result = yield (new File())->validate($value);

            if (!$result->isValid()) {
                return $result;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);

                if ($fileInfo === false) {
                    throw new FileInfo('Could not open `magic.mime` database.');
                }

                $mimeType = finfo_file($fileInfo, $value);

                finfo_close($fileInfo);

                if ($mimeType === $this->mimeType) {
                    return succeed();
                }

                return fail('File.MimeType', new Parameter('mimeType', $this->mimeType));
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
