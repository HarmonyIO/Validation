<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Enum\File\Image\Svg;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Enum\File\Image\Svg\Attribute;

class AttributeTest extends TestCase
{
    public function testExistsReturnsFalseWhenAttributeDoesNotExist(): void
    {
        $this->assertFalse((new Attribute())->exists('onclick'));
    }

    public function testExistsReturnsTrueWhenAttributeDoesExist(): void
    {
        $this->assertTrue((new Attribute())->exists('visibility'));
    }
}
