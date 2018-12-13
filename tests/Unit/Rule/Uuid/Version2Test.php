<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Uuid;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Uuid\Version2;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class Version2Test extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Version2::class);
    }

    /**
     * @dataProvider provideInvalidUuids
     */
    public function testValidateFailsWhenPassingAnInvalidV2UuidString(string $uuid): void
    {
        /** @var Result $result */
        $result = wait((new Version2())->validate($uuid));

        $this->assertFalse($result->isValid());
        $this->assertSame('Uuid.Version2', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidUuids
     */
    public function testValidateReturnsTrueWhenPassingAValidV2UuidString(string $uuid): void
    {
        /** @var Result $result */
        $result = wait((new Version2())->validate($uuid));

        $this->assertTrue($result->isValid());
        $this->assertNull($result->getFirstError());
    }

    /**
     * @return string[]
     */
    public function provideInvalidUuids(): array
    {
        return [
            ['00000000-0000-0000-0000-000000000001'],
            ['5ba4e31e-e99d-11e8-9f32-f2801f1b9fd1'],
            ['5ba4eb16-e99d-21e8-9f32-f2801f1b9fdg'],
            ['5ba4e490-e99d-31e8-9f32-f2801f1b9fd1'],
            ['5ba4e5e4-e99d-41e8-9f32-f2801f1b9fd1'],
            ['5ba4e72e-e99d-51e8-9f32-f2801f1b9fd1'],
            ['5ba4eb16-e99d-61e8-9f32-f2801f1b9fd1'],
            ['incorrect-string'],
            [''],
        ];
    }

    /**
     * @return string[]
     */
    public function provideValidUuids(): array
    {
        return [
            ['5ba4dff4-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4e31e-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4e490-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4e5e4-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4e72e-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4eb16-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4ec7e-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4edbe-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4eefe-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4f034-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4f174-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4f2be-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4f6b0-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4f804-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4f944-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4fa7a-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4fbba-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4fcfa-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba5009c-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba50236-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba50466-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba506b4-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba508e4-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba50a56-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba50ba0-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba50f60-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba510b4-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba511f4-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba5132a-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba51474-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba516c2-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba51c1c-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba51d98-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba51ec4-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba51ffa-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba52130-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba5228e-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba523c4-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba528e2-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba52a4a-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba52b80-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba52ce8-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba52e1e-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba52fea-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba5318e-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba534e0-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba53670-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba537d8-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba5392c-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba53a62-e99d-21e8-9f32-f2801f1b9fd1'],
        ];
    }
}
