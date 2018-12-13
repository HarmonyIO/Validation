<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Result;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Parameter;

class ParameterTest extends TestCase
{
    public function testGetKey(): void
    {
        $this->assertSame('TheKey', (new Parameter('TheKey', 'TheValue'))->getKey());
    }

    public function testGetValue(): void
    {
        $this->assertSame('TheValue', (new Parameter('TheKey', 'TheValue'))->getValue());
    }
}
