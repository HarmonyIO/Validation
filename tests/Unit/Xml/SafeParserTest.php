<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Xml;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Exception\InvalidXml;
use HarmonyIO\Validation\Xml\SafeParser;

class SafeParserTest extends TestCase
{
    public function testConstructorThrowsOnBrokenXml(): void
    {
        $this->expectException(InvalidXml::class);

        new SafeParser(file_get_contents(TEST_DATA_DIR . '/image/broken.svg'));
    }

    public function testGetElementsByTagNameReturnsNodeList(): void
    {
        $xmlParser = new SafeParser(file_get_contents(TEST_DATA_DIR . '/image/example.svg'));

        $this->assertInstanceOf(\DOMNodeList::class, $xmlParser->getElementsByTagName('*'));
    }

    public function testGetElementsByTagNameReturnsNodes(): void
    {
        $xmlParser = new SafeParser(file_get_contents(TEST_DATA_DIR . '/image/example.svg'));

        $this->assertSame(3, $xmlParser->getElementsByTagName('*')->count());
    }
}
