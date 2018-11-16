<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Uuid;

use HarmonyIO\PHPUnitExtension\TestCase;
use HarmonyIO\Validation\Rule\Rule;
use HarmonyIO\Validation\Rule\Uuid\Version5;

class Version5Test extends TestCase
{
    public function testRuleImplementsInterface(): void
    {
        $this->assertInstanceOf(Rule::class, new Version5());
    }

    public function testValidateReturnsFalseWhenPassingAnInteger(): void
    {
        $this->assertFalse((new Version5())->validate(1));
    }

    public function testValidateReturnsFalseWhenPassingAFloat(): void
    {
        $this->assertFalse((new Version5())->validate(1.1));
    }

    public function testValidateReturnsFalseWhenPassingABoolean(): void
    {
        $this->assertFalse((new Version5())->validate(true));
    }

    public function testValidateReturnsFalseWhenPassingAnArray(): void
    {
        $this->assertFalse((new Version5())->validate([]));
    }

    public function testValidateReturnsFalseWhenPassingAnObject(): void
    {
        $this->assertFalse((new Version5())->validate(new \DateTimeImmutable()));
    }

    public function testValidateReturnsFalseWhenPassingNull(): void
    {
        $this->assertFalse((new Version5())->validate(null));
    }

    public function testValidateReturnsFalseWhenPassingAResource(): void
    {
        $resource = fopen('php://memory', 'r');

        if ($resource === false) {
            $this->fail('Could not open the memory stream used for the test');

            return;
        }

        $this->assertFalse((new Version5())->validate($resource));

        fclose($resource);
    }

    public function testValidateReturnsFalseWhenPassingACallable(): void
    {
        $this->assertFalse((new Version5())->validate(static function (): void {
        }));
    }

    /**
     * @dataProvider provideValidUuids
     */
    public function testValidateReturnsTrueWhenPassingAValidV4UuidString(string $uuid): void
    {
        $this->assertTrue((new Version5())->validate($uuid));
    }

    /**
     * @dataProvider provideInvalidUuids
     */
    public function testValidateReturnsFalseWhenPassingAnInvalidV4UuidString(string $uuid): void
    {
        $this->assertFalse((new Version5())->validate($uuid));
    }

    /**
     * @return string[]
     */
    public function provideValidUuids(): array
    {
        return [
            ['d0e67d41-ab5d-5b49-8ed6-17e9bffc4621'],
            ['7f365918-09f0-5031-997b-9f905f12cbbb'],
            ['8ec897de-d36c-5222-b81b-597982efb660'],
            ['2e2f3b87-a10c-506e-ba17-e90f2085b9dd'],
            ['fd28ece3-5691-5310-9fb9-5062055d6f66'],
            ['5f1433ba-b431-5c0a-b620-1ed17e968a65'],
            ['25f781f8-5820-59e0-9fef-72d454a7572e'],
            ['c6e9b696-97fe-5c08-9491-54944b02bcef'],
            ['20b875e2-0516-51d1-82f3-53b7bcf27831'],
            ['880eab39-542c-5ddb-8b7b-9a61d8ff46d5'],
            ['80b19e38-307f-5243-b712-9e085bff3f32'],
            ['c20b615d-d762-5965-9658-8ea7683569be'],
            ['67b56736-15a8-57c6-bdd3-cdad053afbd6'],
            ['2dfb4075-9c53-5f2a-b87b-1e8aa9a8c676'],
            ['2844298f-16fb-5d3c-9a1f-82c24e0c447a'],
            ['5db26282-f53b-5bb6-907c-fb4e27dd7cc4'],
            ['f3eddace-daad-54ef-8279-9bc28a738ea9'],
            ['c379aa38-019c-539e-adc0-a96cf5593c61'],
            ['09e7b2f8-3e2d-5122-9648-98d4cc06ade8'],
            ['0352e2a2-55b2-5263-a4d2-138400f40ce0'],
            ['1e3a6a97-0d47-5979-a677-cf3ded49e1e8'],
            ['0942f13e-a2d7-55a5-b386-d6dba90bc34c'],
            ['a15aadd1-09dd-5768-bead-59f5a56e9cd5'],
            ['507f36e0-90bf-55a4-888e-646f84323c61'],
            ['d76e473c-a86f-5b67-9afe-7c94253006e5'],
            ['4c051c5c-2147-567b-ae89-0befd45eb5b2'],
            ['27bf07e6-b83d-5e76-a5c0-91f2df8b98ab'],
            ['7f330ab2-bbe8-537a-a24e-f6bac864db63'],
            ['dd6b7eaa-3e1a-5201-8ad4-9a5d2078eb9b'],
            ['45ebfabf-27f8-51e1-bb66-8f4afc058278'],
            ['9e974c4c-3faa-5843-9587-1498b8f47a23'],
            ['409b1084-1e5d-5bf6-a5dd-59bc8ba34ec6'],
            ['589776bd-4292-514d-a499-881251bd8b01'],
            ['8a762724-c712-54eb-ae84-60ce50d7a2dc'],
            ['60a515db-99cb-543f-8cdf-d09cb17f8471'],
            ['ec445b0a-8cf4-56d0-8c84-1856ec71e99b'],
            ['7b902391-e7d1-5ed8-b36d-aec87b518eac'],
            ['1fd8dd23-dd3b-5b87-8a27-0cdf2425dae8'],
            ['a9eedc05-2906-5826-9f71-b45a057c719b'],
            ['3a42a0b0-e386-52c1-986f-ae427cd92fe4'],
            ['9e494248-f729-53c5-b46c-0046e3f7758b'],
            ['776e7086-d260-5c06-a4c7-4fa52a565f22'],
            ['c6f028ee-ab4f-513b-9072-8e4251e7c1db'],
            ['5f4d39a7-254a-5b72-8b44-ec3030196b26'],
            ['cd7cc52a-6af5-5104-884d-122b6ea48a01'],
            ['3f72aa4e-3e78-522c-895c-78da1c91413a'],
            ['145e9195-2b89-5e57-b753-e91e60f10dad'],
            ['12a2a81b-096c-5416-b052-4a9487f13449'],
            ['ac389e7a-5d12-53d9-8b6b-1a2b39733c74'],
            ['50b099bf-4536-5998-9392-bade6dc6e11a'],
        ];
    }

    /**
     * @return string[]
     */
    public function provideInvalidUuids(): array
    {
        return [
            ['00000000-0000-0000-0000-000000000001'],
            ['5ba4e31e-e99d-11e8-9f32-f2801f1b9fd1'],
            ['5ba4e31e-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4e490-e99d-31e8-9f32-f2801f1b9fd1'],
            ['5ba4e490-e99d-41e8-9f32-f2801f1b9fd1'],
            ['5ba4e72e-e99d-51e8-9f32-f2801f1b9fdg'],
            ['5ba4eb16-e99d-61e8-9f32-f2801f1b9fd1'],
            ['incorrect-string'],
            [''],
        ];
    }
}
