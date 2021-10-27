<?php

namespace Mpyw\UuidUlidConverter;

final class Converter
{
    private const ULID_TABLE = '0123456789ABCDEFGHJKMNPQRSTVWXYZ';
    private const ULID_REVERSE_TABLE = [
        0 => 0,
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        'A' => 10,
        'B' => 11,
        'C' => 12,
        'D' => 13,
        'E' => 14,
        'F' => 15,
        'G' => 16,
        'H' => 17,
        'J' => 18,
        'K' => 19,
        'M' => 20,
        'N' => 21,
        'P' => 22,
        'Q' => 23,
        'R' => 24,
        'S' => 25,
        'T' => 26,
        'V' => 27,
        'W' => 28,
        'X' => 29,
        'Y' => 30,
        'Z' => 31,
    ];

    /**
     * Convert ULID to UUID.
     */
    public static function ulidToUuid(string $ulid, bool $uppercase = false): string
    {
        if (!\preg_match('/\A[0-9A-Z]{26}\z/i', $ulid)) {
            throw new BadFormatException('Invalid ULID.');
        }

        $ord = \array_map(
            fn (string $char) => self::ULID_REVERSE_TABLE[$char],
            \str_split(\strtoupper($ulid)),
        );

        // cf. https://github.com/valohai/ulid2/blob/7e6093aae8c99dd68fa4f849718dbb219fde8b9f/ulid2.py#L126-L143
        $uuid = \preg_replace(
            '/^(.{8})(.{4})(.{4})(.{4})(.{12})$/',
            '$1-$2-$3-$4-$5',
            \bin2hex(\pack(
                'C*',
                (($ord[0] << 5) | $ord[1]),
                (($ord[2] << 3) | ($ord[3] >> 2)),
                (($ord[3] << 6) | ($ord[4] << 1) | ($ord[5] >> 4)),
                (($ord[5] << 4) | ($ord[6] >> 1)),
                (($ord[6] << 7) | ($ord[7] << 2) | ($ord[8] >> 3)),
                (($ord[8] << 5) | $ord[9]),
                (($ord[10] << 3) | ($ord[11] >> 2)),
                (($ord[11] << 6) | ($ord[12] << 1) | ($ord[13] >> 4)),
                (($ord[13] << 4) | ($ord[14] >> 1)),
                (($ord[14] << 7) | ($ord[15] << 2) | ($ord[16] >> 3)),
                (($ord[16] << 5) | $ord[17]),
                (($ord[18] << 3) | $ord[19] >> 2),
                (($ord[19] << 6) | ($ord[20] << 1) | ($ord[21] >> 4)),
                (($ord[21] << 4) | ($ord[22] >> 1)),
                (($ord[22] << 7) | ($ord[23] << 2) | ($ord[24] >> 3)),
                (($ord[24] << 5) | $ord[25]),
            )),
        );

        return $uppercase ? \strtoupper($uuid) : $uuid;
    }

    /**
     * Convert UUID to ULID.
     */
    public static function uuidToUlid(string $uuid, bool $lowercase = false): string
    {
        if (!\preg_match('/\A[0-9A-Z]{8}(?:-[0-9A-Z]{4}){3}-[0-9A-Z]{12}\z/i', $uuid)) {
            throw new BadFormatException('Invalid UUID.');
        }

        $chr = \array_values(\unpack(
            'C*',
            \hex2bin(\str_replace('-', '', $uuid)),
        ));

        // c.f. https://github.com/valohai/ulid2/blob/7e6093aae8c99dd68fa4f849718dbb219fde8b9f/ulid2.py#L73-L100
        $ulid = \implode('', [
            self::ULID_TABLE[($chr[0] & 224) >> 5],
            self::ULID_TABLE[$chr[0] & 31],
            self::ULID_TABLE[($chr[1] & 248) >> 3],
            self::ULID_TABLE[(($chr[1] & 7) << 2) | (($chr[2] & 192) >> 6)],
            self::ULID_TABLE[($chr[2] & 62) >> 1],
            self::ULID_TABLE[(($chr[2] & 1) << 4) | (($chr[3] & 240) >> 4)],
            self::ULID_TABLE[(($chr[3] & 15) << 1) | (($chr[4] & 128) >> 7)],
            self::ULID_TABLE[($chr[4] & 124) >> 2],
            self::ULID_TABLE[(($chr[4] & 3) << 3) | (($chr[5] & 224) >> 5)],
            self::ULID_TABLE[$chr[5] & 31],
            self::ULID_TABLE[($chr[6] & 248) >> 3],
            self::ULID_TABLE[(($chr[6] & 7) << 2) | (($chr[7] & 192) >> 6)],
            self::ULID_TABLE[($chr[7] & 62) >> 1],
            self::ULID_TABLE[(($chr[7] & 1) << 4) | (($chr[8] & 240) >> 4)],
            self::ULID_TABLE[(($chr[8] & 15) << 1) | (($chr[9] & 128) >> 7)],
            self::ULID_TABLE[($chr[9] & 124) >> 2],
            self::ULID_TABLE[(($chr[9] & 3) << 3) | (($chr[10] & 224) >> 5)],
            self::ULID_TABLE[$chr[10] & 31],
            self::ULID_TABLE[($chr[11] & 248) >> 3],
            self::ULID_TABLE[(($chr[11] & 7) << 2) | (($chr[12] & 192) >> 6)],
            self::ULID_TABLE[($chr[12] & 62) >> 1],
            self::ULID_TABLE[(($chr[12] & 1) << 4) | (($chr[13] & 240) >> 4)],
            self::ULID_TABLE[(($chr[13] & 15) << 1) | (($chr[14] & 128) >> 7)],
            self::ULID_TABLE[($chr[14] & 124) >> 2],
            self::ULID_TABLE[(($chr[14] & 3) << 3) | (($chr[15] & 224) >> 5)],
            self::ULID_TABLE[$chr[15] & 31],
        ]);

        return $lowercase ? \strtolower($ulid) : $ulid;
    }
}
