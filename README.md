# PHP Office Helper

This package implements `phpoffice/phpspreadsheet`.

## Usage
Implement new document class, extending `BaseDocument` or `BaseExcelDocument`, just like in `example` folder.

Implement new document helper class, which extends `DocumentHelper` and introduce a new method inside of it, just like below.
```php
/**
 * Returns filename
 *
 * @param \DateTime $from
 * @param \DateTime $to
 * @return string
 */
public function createExcelSpreadsheetActivityLog(\DateTime $from, \DateTime $to): string
{
    /** @var ExampleSpreadsheet $document */
    $document = $this->documentFactory->createByClassName(ExampleSpreadsheet::class);
    $document->setRange($from, $to);
    return $this->documentFactory->save($document);
}
```

## Installing

`$ composer require 7interactive/php-office-helper`


## License

MIT