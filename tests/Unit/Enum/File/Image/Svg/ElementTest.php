<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Enum\File\Image\Svg;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Enum\File\Image\Svg\Element;

class ElementTest extends TestCase
{
    public function testExistsReturnsFalseWhenElementDoesNotExist(): void
    {
        $this->assertFalse((new Element())->exists('fake-element'));
    }

    public function testExistsReturnsTrueWhenElementDoesExist(): void
    {
        $this->assertTrue((new Element())->exists('svg'));
        $this->assertTrue((new Element())->exists('a'));
    }
}
