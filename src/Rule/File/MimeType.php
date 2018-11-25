<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule\File;

use Amp\Promise;
use Amp\Success;
use HarmonyIO\Validation\Exception\FileInfo;
use HarmonyIO\Validation\Rule\FileSystem\File;
use HarmonyIO\Validation\Rule\Rule;
use function Amp\call;
use function Amp\ParallelFunctions\parallel;

class MimeType implements Rule
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
        if (!is_string($value)) {
            return new Success(false);
        }

        return call(function () use ($value) {
            if (!yield (new File())->validate($value)) {
                return false;
            }

            return parallel(function () use ($value) {
                // @codeCoverageIgnoreStart
                $fileInfo = finfo_open(FILEINFO_MIME_TYPE);

                if ($fileInfo === false) {
                    throw new FileInfo('Could not open `magic.mime` database.');
                }

                $mimeType = finfo_file($fileInfo, $value);

                finfo_close($fileInfo);

                return $mimeType === $this->mimeType;
                // @codeCoverageIgnoreEnd
            })();
        });
    }
}
