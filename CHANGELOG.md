## Db-Connector ChangeLog
*Db-Connector follows [Semantic Versioning 2.0.0](https://semver.org/)*

### Next Major
*Released - TBD*

#### Removed -
- Dropped support for PHP 7.1

#### Features -

#### Internals -
- Added Docker containers for development
- Updated development dependencies
- Hard coded .env.test.local filename removed in functional tests. Instead, you must
set `FUNC_TEST_ENV_FILE` in the environment before running functional tests. `.env.test.local`
has been set in the mariadb docker-compose.yml file for local testing. 


### v2.1.0
*Released - 2019-05-02*

#### Features -

- ArrayConfigAdapter enables you to set the configuration using an array.
- Added the ability to use SSL connections with the json config adapter.

#### Internals -
- Updated composer dependencies
- Inherited method declarations updated for PHPUnit 8
- PHP Code Sniffer added
- Improved Test Coverage

---

### v2.0.1
*Released - 2019-02-21*

#### Fixes -
- Type Error when calling sslVerify(). Argument type is bool, string was indicated in the type hint.

---

### v2.0.0
*Released - 2019-02-21*

#### Features -
- SSL/TLS Connections now supported. Limited to the EnvConfigAdapter.
- Updated Documentation
- Minor refactoring within the code base.

---

### v1.1.1
*Released - 2019-02-17*

#### Fixes -
- Fixed inability to use any port other than 3306 for the connection
regardless of what port was specified in the configuration.

---

### v1.1.0
*Released - 2018-12-09*

#### New features -
- EnvConfigAdapter add's the ability to parse the required config param's from
the server environment.

- Added roave/security-advisories to composer. Provides security check's on
required package's within a project.

#### Fixes -
- Fixed an issue where `getConnection()` was including `dbname` in the sql 
connection string even though the `$database` configObject property was empty / null.
---
### v1.0.0
*Released - 2018-11-29*

Initial versioned release of Db-Connector