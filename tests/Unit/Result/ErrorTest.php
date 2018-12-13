<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Result;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Result\Error;
use HarmonyIO\Validation\Result\Parameter;

class ErrorTest extends TestCase
{
    public function testGetMessage(): void
    {
        $this->assertSame('Test.Message', (new Error('Test.Message'))->getMessage());
    }

    public function testGetParametersWithoutParameters(): void
    {
        $this->assertCount(0, (new Error('Test.Message'))->getParameters());
    }

    public function testGetParametersWithSingleParameter(): void
    {
        $error = new Error('Test.Message', new Parameter('TestKey', 'TestValue'));

        $this->assertCount(1, $error->getParameters());
        $this->assertSame('TestKey', $error->getParameters()[0]->getKey());
        $this->assertSame('TestValue', $error->getParameters()[0]->getValue());
    }

    public function testGetParametersWithMultipleParameters(): void
    {
        $error = new Error(
            'Test.Message',
            new Parameter('TestKey1', 'TestValue1'),
            new Parameter('TestKey2', 'TestValue2')
        );

        $this->assertCount(2, $error->getParameters());
        $this->assertSame('TestKey1', $error->getParameters()[0]->getKey());
        $this->assertSame('TestValue1', $error->getParameters()[0]->getValue());
        $this->assertSame('TestKey2', $error->getParameters()[1]->getKey());
        $this->assertSame('TestValue2', $error->getParameters()[1]->getValue());
    }
}
