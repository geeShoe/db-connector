# db-connector
Db-Connector is a tool set to help manage and provide database connection's 
within your PHP application.

## Getting Started

Db-Connector is intended to be fully compliant with 
[PSR-1](https://www.php-fig.org/psr/psr-1/),
[PSR-2](https://www.php-fig.org/psr/psr-2/),
 & [PSR-4](https://www.php-fig.org/psr/psr-4/)

### Prerequisites

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

### Installing

To add Db-Connector to your project, run:

```
composer require geeshoe/db-connecter
```

### Configure

Db-Connector configuration parameters can be set using various file formats.
However, JSON is the only format currently supported out of the box. Other 
formats such as .env, yaml, etc.. are soon to follow.

It is possible to brew your own config adapter using the AbstractConfigObject in
the meantime.

Copy the included dbConnector_DIST.json to a secure location outside of your
projects web root. 
 
Change the values to reflect your database configuration.

```
{
  "dbConnector" : {
    "host": "127.0.0.1",
    "port": "3306",
    "user": "myUsername",
    "password": "SomePassword",
    "database": "OptionalSeeDocumentation",
    "persistent": false
  }
}
```
The ```"database"``` param is not required but must be declared in the config
file. If you prefer to handle which database to use within your application, 
simply set ```"database"``` to ```""```.

Persistent connections can be enabled by setting ```"persistent"``` to
```true```.

For more information on [Persistent Connections](http://php.net/manual/en/pdo.connections.php).

### Documentation

More extensive documentation on Db-Connector is to be released soon. In the
meantime, all of the methods and properties are well documented within the
code base.

### Authors

* **Jesse Rushlow** - *Lead developer* - [geeShoe Development](http://geeshoe.com)

Source available at (https://github.com/geeshoe/db-connector)

For questions, comments, or rant's, drop me a line at 
```
jr (at) geeshoe (dot) com
```