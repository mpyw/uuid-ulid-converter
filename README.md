# uuid-ulid-converter

[![Build Status](https://github.com/mpyw/uuid-ulid-converter/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/mpyw/uuid-ulid-converter/actions) [![Coverage Status](https://coveralls.io/repos/github/mpyw/uuid-ulid-converter/badge.svg?branch=master)](https://coveralls.io/github/mpyw/uuid-ulid-converter?branch=master)

[UUID](https://datatracker.ietf.org/doc/html/rfc4122) &lt;=&gt; [ULID](https://github.com/ulid/spec) bidirectional converter

## Installing

```
composer require mpyw/uuid-ulid-converter
```

## Requirements

| Package | Version |
|:---|:---|
| PHP | <code>^8.2</code> |

> [!NOTE]
> Older versions have outdated dependency requirements. If you cannot prepare the latest environment, please refer to past releases.

## API

```php
public static Converter::uuidToUlid(string $uuid, bool $lowercase = false): string
public static Converter::ulidToUuid(string $ulid, bool $uppercase = false): string
```


> [!IMPORTANT]
> **UUID is lowercase by default, whereas ULID is uppercase by default.**

## Usage

### Basic

```php
use Mpyw\UuidUlidConverter\Converter;

var_dump(Converter::ulidToUuid('61862H2EWP9TCTRX3MJ15XNY7X'));
// string(36) "c1418511-3b96-4e99-ac74-74904bdaf8fd"

var_dump(Converter::uuidToUlid('c1418511-3b96-4e99-ac74-74904bdaf8fd'));
// string(26) "61862H2EWP9TCTRX3MJ15XNY7X"
```

### Advanced

The following workarounds are particularly useful in the PostgreSQL,
which does not support ULID but does support UUID.
Storing ULID as native UUID is more efficient than storing original ULID as strings.

Introduce advanced usages with [robinvdvleuten/php-ulid](https://github.com/robinvdvleuten/php-ulid).

#### Generate UUID-styled ULID

```php
use Mpyw\UuidUlidConverter\Converter;
use Ulid\Ulid;

// Use generated UUID as primary key
$uuid = Converter::ulidToUuid((string)Ulid::generate());
```

#### Timestamp range of UUID-styled ULID

```php
use Mpyw\UuidUlidConverter\Converter;
use Ulid\Ulid;

$dates = [
    new \DateTimeImmutable('2020-01-01 00:00:00.000 UTC'),
    new \DateTimeImmutable('2020-01-02 00:00:00.000 UTC'),
];

function createUuidRange(array $dates): array
{
    $createPart = fn (\DateTimeInterface $date, bool $isEnd) => Converter::ulidToUuid(
        Ulid::fromTimestamp(round((int)$date->format('Uu') / 1000))->getTime()
        . str_repeat($isEnd ? 'Z' : '0', 16),
    );
    return [
        $createPart($dates[0], false),
        $createPart($dates[1], true),
    ];
}

$uuids = createUuidRange($dates);
/*
array(2) {
  [0]=>
  string(36) "016f5e66-e800-0000-0000-000000000000"
  [1]=>
  string(36) "016f638d-4400-ffff-ffff-ffffffffffff"
}
*/
```
