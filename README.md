## Monolog PDO

[Monolog](https://github.com/Seldaek/monolog) handler used to write log files to a MySQL database.

- [License](#license)
- [Author](#author)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)

## License

This project is open source and available under the [MIT License](LICENSE).

## Author

<img src="https://cdn1.onbayfront.com/bfm/brand/bfm-logo.svg" alt="Bayfront Media" width="250" />

- [Bayfront Media homepage](https://www.bayfrontmedia.com?utm_source=github&amp;utm_medium=direct)
- [Bayfront Media GitHub](https://github.com/bayfrontmedia)

## Requirements

* PHP >= 8.0 (Tested up to `8.4`)
* `PDO` PHP extension

## Installation

```
composer require bayfrontmedia/monolog-pdo
```

## Usage

Before pushing this handler to a `Logger` instance, you must first create the necessary database table to store the records.

The table can be created using the `up` method, and removed using the `down` method.

The constructor requires a `PDO` instance, and the table name you wish to use:

```php
use Bayfront\MonologPDO\PDOHandler;

/** @var PDO $pdo */
$handler = new PDOHandler($pdo, 'table_name');

$handler->up();
```

Once the table has been created, the handler can be pushed to a `Logger` instance:

```php
use Bayfront\MonologPDO\PDOHandler;
use Monolog\Logger;

$log = new Logger('channel_name');

/** @var PDO $pdo */
$handler = new PDOHandler($pdo, 'table_name');

$log->pushHandler($handler);
```

From here, your log records should appear in the MySQL table.