## Db-Connector ChangeLog
*Db-Connector follows [Semantic Versioning 2.0.0](https://semver.org/)*

### TBD
#### New features -
- EnvConfigAdapter add's the ability to parse the required config param's from
the server environment.

- Added roave/security-advisories to composer. Provides security check's on
required package's within a project.

#### Fixes -
- Fixed an issue where `getConnection()` was including `dbname` in the sql 
connection string even though the `$database` configObject property was empty / null.

### v1.0.0
*Released - 2018-11-29*

Initial versioned release of Db-Connector