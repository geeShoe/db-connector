## Db-Connector ChangeLog
*Db-Connector follows [Semantic Versioning 2.0.0](https://semver.org/)*

### v1.1.1
*Released - 2019-02-17*

#### Fixes -
- Fixed inability to use any port other than 3306 for the connection
regardless of what port was specified in the configuration.

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