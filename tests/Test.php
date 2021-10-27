<?php

namespace Mpyw\UuidUlidConverter\Tests;

use Mpyw\UuidUlidConverter\Converter;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function patterns(): array
    {
        // patterns are generated by
        //    Python built-in uuid module: https://docs.python.org/3/library/uuid.html
        //    @mdomke's Python module: https://github.com/mdomke/python-ulid
        return [
            ['c1418511-3b96-4e99-ac74-74904bdaf8fd', '61862H2EWP9TCTRX3MJ15XNY7X'],
            ['10dfdf49-f86b-40be-a05a-649a90f21f99', '0GVZFMKY3B82ZA0PK4KA8F47WS'],
            ['c8c2710f-f5ec-437e-8e3f-c3fa23048c86', '68R9RGZXFC8DZ8WFY3Z8HG9346'],
            ['193053c4-0817-4ac5-a393-9c87f4e2ba49', '0S619W820Q9B2T74WWGZTE5EJ9'],
            ['3576f1da-6587-471f-8033-6bfeb2a8af06', '1NEVRXMSC78WFR0CVBZTSAHBR6'],
            ['5cec0602-1033-492e-a170-0015e6eef481', '2WXG30441K94QA2W002QKEXX41'],
            ['b0c566bf-9557-4113-b459-0f33de57ef17', '5GRNKBZ5AQ849V8P8F6FF5FVRQ'],
            ['64cb2eaf-09f9-4173-94de-3bb3770e087b', '34SCQAY2FS85SS9QHVPDVGW23V'],
            ['8df47932-6d6f-46ac-a7b5-79e2a40b34a1', '4DYHWK4VBF8TPAFDBSWAJ0PD51'],
            ['2aeb7fd6-adad-4158-ae78-07bc85720600', '1AXDZXDBDD85CAWY07QJ2Q41G0'],
        ];
    }

    /**
     * @dataProvider patterns
     */
    public function testUlidToUuid(string $uuid, string $ulid): void
    {
        $this->assertSame($uuid, Converter::ulidToUuid($ulid));
    }

    /**
     * @dataProvider patterns
     */
    public function testUuidToUlid(string $uuid, string $ulid): void
    {
        $this->assertSame($ulid, Converter::uuidToUlid($uuid));
    }

    public function testLowercaseUlid(): void
    {
        $this->assertSame(
            '61862h2ewp9tctrx3mj15xny7x',
            Converter::uuidToUlid('c1418511-3b96-4e99-ac74-74904bdaf8fd', true),
        );
    }

    public function testUpperCaseUuid(): void
    {
        $this->assertSame(
            'C1418511-3B96-4E99-AC74-74904BDAF8FD',
            Converter::ulidToUuid('61862H2EWP9TCTRX3MJ15XNY7X', true),
        );
    }
}
