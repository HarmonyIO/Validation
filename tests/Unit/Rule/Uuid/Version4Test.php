<?php declare(strict_types=1);

namespace HarmonyIO\ValidationTest\Unit\Rule\Uuid;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Uuid\Version4;
use HarmonyIO\ValidationTest\Unit\Rule\StringTestCase;
use function Amp\Promise\wait;

class Version4Test extends StringTestCase
{
    /**
     * @param mixed[] $data
     */
    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName, Version4::class);
    }

    /**
     * @dataProvider provideInvalidUuids
     */
    public function testValidateFailsWhenPassingAnInvalidV4UuidString(string $uuid): void
    {
        /** @var Result $result */
        $result = wait((new Version4())->validate($uuid));

        $this->assertFalse($result->isValid());
        $this->assertSame('Uuid.Version4', $result->getFirstError()->getMessage());
    }

    /**
     * @dataProvider provideValidUuids
     */
    public function testValidateSucceedsWhenPassingAValidV4UuidString(string $uuid): void
    {
        /** @var Result $result */
        $result = wait((new Version4())->validate($uuid));

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
            ['5ba4e31e-e99d-21e8-9f32-f2801f1b9fd1'],
            ['5ba4e490-e99d-31e8-9f32-f2801f1b9fd1'],
            ['5ba4e490-e99d-41e8-9f32-f2801f1b9fdg'],
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
            ['d0e67d41-ab5d-4b49-8ed6-17e9bffc4621'],
            ['7f365918-09f0-4031-997b-9f905f12cbbb'],
            ['8ec897de-d36c-4222-b81b-597982efb660'],
            ['2e2f3b87-a10c-406e-ba17-e90f2085b9dd'],
            ['fd28ece3-5691-4310-9fb9-5062055d6f66'],
            ['5f1433ba-b431-4c0a-b620-1ed17e968a65'],
            ['25f781f8-5820-49e0-9fef-72d454a7572e'],
            ['c6e9b696-97fe-4c08-9491-54944b02bcef'],
            ['20b875e2-0516-41d1-82f3-53b7bcf27831'],
            ['880eab39-542c-4ddb-8b7b-9a61d8ff46d5'],
            ['80b19e38-307f-4243-b712-9e085bff3f32'],
            ['c20b615d-d762-4965-9658-8ea7683569be'],
            ['67b56736-15a8-47c6-bdd3-cdad053afbd6'],
            ['2dfb4075-9c53-4f2a-b87b-1e8aa9a8c676'],
            ['2844298f-16fb-4d3c-9a1f-82c24e0c447a'],
            ['5db26282-f53b-4bb6-907c-fb4e27dd7cc4'],
            ['f3eddace-daad-44ef-8279-9bc28a738ea9'],
            ['c379aa38-019c-439e-adc0-a96cf5593c61'],
            ['09e7b2f8-3e2d-4122-9648-98d4cc06ade8'],
            ['0352e2a2-55b2-4263-a4d2-138400f40ce0'],
            ['1e3a6a97-0d47-4979-a677-cf3ded49e1e8'],
            ['0942f13e-a2d7-45a5-b386-d6dba90bc34c'],
            ['a15aadd1-09dd-4768-bead-59f5a56e9cd5'],
            ['507f36e0-90bf-45a4-888e-646f84323c61'],
            ['d76e473c-a86f-4b67-9afe-7c94253006e5'],
            ['4c051c5c-2147-467b-ae89-0befd45eb5b2'],
            ['27bf07e6-b83d-4e76-a5c0-91f2df8b98ab'],
            ['7f330ab2-bbe8-437a-a24e-f6bac864db63'],
            ['dd6b7eaa-3e1a-4201-8ad4-9a5d2078eb9b'],
            ['45ebfabf-27f8-41e1-bb66-8f4afc058278'],
            ['9e974c4c-3faa-4843-9587-1498b8f47a23'],
            ['409b1084-1e5d-4bf6-a5dd-59bc8ba34ec6'],
            ['589776bd-4292-414d-a499-881251bd8b01'],
            ['8a762724-c712-44eb-ae84-60ce50d7a2dc'],
            ['60a515db-99cb-443f-8cdf-d09cb17f8471'],
            ['ec445b0a-8cf4-46d0-8c84-1856ec71e99b'],
            ['7b902391-e7d1-4ed8-b36d-aec87b518eac'],
            ['1fd8dd23-dd3b-4b87-8a27-0cdf2425dae8'],
            ['a9eedc05-2906-4826-9f71-b45a057c719b'],
            ['3a42a0b0-e386-42c1-986f-ae427cd92fe4'],
            ['9e494248-f729-43c5-b46c-0046e3f7758b'],
            ['776e7086-d260-4c06-a4c7-4fa52a565f22'],
            ['c6f028ee-ab4f-413b-9072-8e4251e7c1db'],
            ['5f4d39a7-254a-4b72-8b44-ec3030196b26'],
            ['cd7cc52a-6af5-4104-884d-122b6ea48a01'],
            ['3f72aa4e-3e78-422c-895c-78da1c91413a'],
            ['145e9195-2b89-4e57-b753-e91e60f10dad'],
            ['12a2a81b-096c-4416-b052-4a9487f13449'],
            ['ac389e7a-5d12-43d9-8b6b-1a2b39733c74'],
            ['50b099bf-4536-4998-9392-bade6dc6e11a'],
        ];
    }
}
