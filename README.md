# uuid-ulid-converter

[UUID](https://datatracker.ietf.org/doc/html/rfc4122) &lt;=&gt; [ULID](https://github.com/ulid/spec) bidirectional converter

## Installing

```
composer require mpyw/uuid-ulid-converter
```

## Usage

```php
use Mpyw\UuidUlidConverter\Converter;

var_dump(Converter::ulidToUuid('61862H2EWP9TCTRX3MJ15XNY7X'));
// string(36) "c1418511-3b96-4e99-ac74-74904bdaf8fd"

var_dump(Converter::uuidToUlid('c1418511-3b96-4e99-ac74-74904bdaf8fd'));
// string(26) "61862H2EWP9TCTRX3MJ15XNY7X"
```

## API

```php
string Converter::uuidToUlid(string $uuid, bool $lowercase = false)
string Converter::ulidToUuid(string $ulid, bool $uppercase = false)
```

**NOTE: UUID is lowercase by default, whereas ULID is uppercase by default.**
