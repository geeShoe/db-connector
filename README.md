# db-connector
[![Build Status](https://travis-ci.com/geeShoe/db-connector.svg?branch=develop)](https://travis-ci.com/geeShoe/db-connector)

Db-Connector is a tool set to help manage and provide database connection's 
within your PHP application.

## Getting Started

Db-Connector is intended to be fully compliant with 
[PSR-1](https://www.php-fig.org/psr/psr-1/),
[PSR-2](https://www.php-fig.org/psr/psr-2/),
 & [PSR-4](https://www.php-fig.org/psr/psr-4/)
 
 Latest Recommended version: v2.1.0 Released May 2nd, 2019
 
 Test coverage: 99% Includes Unit and Functional Tests.
 ```
    Time: 288 ms, Memory: 6.00 MB
    
    OK (40 tests, 94 assertions)
 ```


## Prerequisites

Db-Connector works with both MySQL and MariaDb database's. Support for other
database's is intended to be implemented in future releases.

* PHP 7.1+
* [PDO_MYSQL extension](http://php.net/manual/en/ref.pdo-mysql.php)
* [PDO_JSON extension](http://php.net/manual/en/book.json.php) - If using the
supplied JSON configuration adapter.

To check if the above PHP extension's are enabled, run the following command in
the CLI or use phpinfo() in a non-public page on your web server.

```
phpinfo(); <-- Use with script on web server.
php -i <-- Use with CLI
```

## Installing

To add Db-Connector to your project, run:

```
composer require geeshoe/db-connector
```

## Configure

Db-Connector configuration parameters can be set using various methods. 
JSON, Array, and Environment configuration adapters are available out of the box.
Other formats are soon to follow.

It is possible to brew your own config adapter by extending the AbstractConfigObject.

---
#### Environment Variable Config

The EnvConfigAdapter parse's the following environment variable's for the database
connection:

- `GSD_DB_HOST` - Host name or IP of the database server.
- `GSD_DB_PORT` - Port number of the database server.
- `GSD_DB_USER` - User name used by the database.
- `GSD_DB_PASSWORD` - Password of the user.
- `GSD_DB_PERSISTENT` - Set to either `true` or `false` to enable/disable
persistent connections.
- `GSD_DB_DATABASE` - (Not Required) Select which database to use at the
 connection level rather than at the SQL Statement level. `GSD_DB_DATABASE` does
 not need to be set in the environment if it's not used.
- `GSD_DB_SSL` - Set to either `true` or `false` to enable SSL/TLS.
- `GSD_DB_CA` - Path to Certificate Authority .pem file.
- `GSD_DB_CERT` - Path to Certificate .pem file.
- `GSD_DB_KEY` - Path to Certificate Key .pem file.
- `GSD_DB_VERIFY` - Set to either `true` or `false`. Provides a way to disable the
verification of the server SSL Certificate.

`GSD_DB_CA`, `GSD_DB_CERT`, `GSD_DB_KEY`, & `GSD_DB_VERIFY` are only required if
`GSD_DB_SSL` is set to `true`.

---

#### JSON Config
Copy the included dbConnector_DIST.json to a secure location outside of your
projects public web root. 
 
Change the values to reflect your database configuration.

```
{
  "dbConnector" : {
    "host": "127.0.0.1",
    "port": "3306",
    "user": "myUsername",
    "password": "SomePassword",
    "database": "OptionalSeeDocumentation",
    "persistent": false,
    "ssl": false,
        "sslParams": {
          "ca": "/path/to/ca",
          "cert": "/path/to/cert",
          "key": "/path/to/key",
          "verify": false
        }
  }
}
```
The ```"database"``` param is not required but must be declared in the config
file. If you prefer to handle which database to use within your application, 
simply set ```"database"``` to ```""```.

Persistent connections can be enabled by setting ```"persistent"``` to
```true```.

```sslParams``` are only required if ```ssl``` is set to true. ```ca```, ```cert```,
```key```, & ```verify``` are all optional depending on your environment.

---

### Array Config
Using an array of configuration param's is possible by using the ArrayConfigAdapter.
An array of connection param's is required by the ArrayConfigAdapter __construct() 
method.

```
$config = [
        'host' => '127.0.0.1',
        'port' => 1234,
        'user' => 'unit',
        'password' => 'test',
        'persistent' => true,
        'ssl' => false,
        'ca' => '/path/to/file,
        'cert' => '/path/to/file,
        'key' => '/path/to/file,
        'verify' => true
    ];
```

For more information on [Persistent Connections](http://php.net/manual/en/pdo.connections.php).

## Usage

Determine which method you want to use for parsing the database credentials.

Using the EnvConfigAdapter:
```
use Geeshoe\DbConnector\ConfigAdapter\EnvConfigAdapter;

$configAdapter = new EnvConfigAdapter();
$configAdapter->initialize();

$credentialsObject = $configAdapter->getParams();
```

Using the JsonConfigAdapter:
```
use Geeshoe\DbConnector\ConfigAdapter\JsonConfigAdapter;

$configAdapter = new JsonConfigAdapter('/path/to/dbConnector.json');
$configAdapter->initialize();

$credentialsObject = $configAdapter->getParams();
```

Using the ArrayConfigAdapter:
```
use Geeshoe\DbConnector\ConfigAdapter\ArrayConfigAdapter;

$configAdapter = new ArrayConfigAdapter(['host'=> 'localhost', 'port' => 1234]);
$configAdapter->initialize();

$credentialsObject = $configAdapter->getParams();
```

After calling getParams() from either of the above method's, create a new PDO
database connection as follow's:
```
$connector = new DbConnector($credentialsObject);
$dbc = $connector->getConnection();
```

`getConnection()` return's a new [PDO object](http://php.net/manual/en/book.pdo.php) that is ready to use. 

## Documentation

More extensive documentation on Db-Connector is to be released soon. In the
meantime, all of the methods and properties are well documented within the
code base.

## Authors

* **Jesse Rushlow** - *Lead developer* - [geeShoe Development](http://geeshoe.com)

Source available at (https://github.com/geeshoe/db-connector)

For questions, comments, or rant's, drop me a line at 
```
jr (at) geeshoe (dot) com
```