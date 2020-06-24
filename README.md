# csv

A package with classes to manage reading and writing of csv files.

## Installation

Add to your project using composer:

```
composer require talkingbit/csv
```

## Basic usage

### Reader

Reader allows us to read the contents of a given file in the file system. Contents are yielded using a Generator. Typical usage could be like this. Rows will be read as plain arrays.

```php
use TalkingBit\Csv\Reader\Reader;

$reader = new Reader();
$filePath = '/path/to/file.csv';
$rows = $reader
    ->fromFile($filePath)
    ->readAll();

foreach ($rows as $row) {
    // Do whatever you need
}
```

If the file has csv headers, you can use the following setup, so rows will be read as associative arrays:

```php
use TalkingBit\Csv\Reader\Reader;

$reader = new Reader();
$filePath = '/path/to/file.csv';
$rows = $reader
    ->fromFile($filePath)
    ->withHeaders()
    ->readAll();

foreach ($rows as $row) {
    // Do whatever you need
}
```

Also, you can map rows to a simple Dto, provided that all relevant fields are public:

```php
use TalkingBit\Csv\Reader\Reader;
use TalkingBit\Csv\Reader\Mapper\DtoMapper;

$reader = new Reader();
$filePath = '/path/to/file.csv';
$rows = $reader
    ->fromFile($filePath)
    ->withHeaders()
    ->usingMapper(new DtoMapper(MyDto::class))
    ->readAll();

foreach ($rows as $row) {
    // Do whatever you need
}
```

### Writer

Writer allows us to write data to a CSV file.

A row can be a plain array:

```php
use TalkingBit\Csv\Writer\Writer;

$writer = new Writer();
$writer
    ->toFile('/path/to/file.csv')
    ->writeRow([123, 'My name']);
```

A row can be an associative array. In this case, keys will be used as CSV headers.

```php
use TalkingBit\Csv\Writer\Writer;

$writer = new Writer();
$writer
    ->toFile('/path/to/file.csv')
    ->writeRow(['id' => 123, 'name' => 'My name']);
```

Also, you can write Dto directly to CSV files. Dtos will be treated as if they were associative arrays.

```php
use TalkingBit\Csv\Writer\Writer;

$writer = new Writer();

$dto = new MyDto();
$dto->id = 123;
$dto->name = 'My name';

$writer
    ->toFile('/path/to/file.csv')
    ->writeRow($dto);
```

## Configuration

You can customize delimiters and enclosure characters:

```php
$writer
    ->toFile('/path/to/file.csv')
    ->withDelimiter(',')
    ->withEnclosure('"')
    ->writeRow($dto);
```

## Custom Mappers for Reader

You can create custom mappers for the Reader implementing the following interface:

```php
interface RowMapperInterface
{
    public function map(array $line, ?array $headers = null);
}
```

The `$line` parameter contains the row data from the file. The `$headers` contains csv headers if found. You can return any type, so you are free to do things like:

* Build application objects.
* Make calculations with input data.
* Validate input data.

## Contribute

Feel free to open issues or pull requests.
