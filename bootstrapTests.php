<?php
/**
 * Copyright 2018 Jesse Rushlow - Geeshoe Development
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use org\bovigo\vfs\vfsStream;

require __DIR__ . '/vendor/autoload.php';

$stream = vfsStream::setup('unitTests');
vfsStream::newFile('config1', 0000)->at($stream);
vfsStream::newFile('config2')->at($stream);
vfsStream::newFile('config3')->at($stream);
vfsStream::newFile('hostname')->at($stream);
vfsStream::newFile('port')->at($stream);
vfsStream::newFile('database')->at($stream);
vfsStream::newFile('username')->at($stream);
vfsStream::newFile('password')->at($stream);
vfsStream::newFile('persistent')->at($stream);
vfsStream::newFile('ssl')->at($stream);

file_put_contents('vfs://unitTests/config2', '{"someConfig": {}}');

file_put_contents(
    'vfs://unitTests/hostname',
    '{"dbConnector":{}}'
);
file_put_contents(
    'vfs://unitTests/port',
    '{"dbConnector":{"host": "host"}}'
);
file_put_contents(
    'vfs://unitTests/database',
    '{"dbConnector":{"host": "host","port":12}}'
);
file_put_contents(
    'vfs://unitTests/username',
    '{"dbConnector":{"host": "host","port":12,"database":""}}'
);
file_put_contents(
    'vfs://unitTests/password',
    '{"dbConnector":{"host": "host","port":12,"database":"","user":"user"}}'
);
file_put_contents(
    'vfs://unitTests/persistent',
    '{"dbConnector":{"host": "host","port":12,"database":"","user":"user","password":"pass"}}'
);

file_put_contents(
    'vfs://unitTests/config3',
    '{"dbConnector":{"host": "host","port":12,"database":"db","user":"user","password":"pass", "persistent":true}}'
);

$ssl = <<< "EOT"
{
  "dbConnector": {
    "host": "host",
    "port": 1234,
    "database": "",
    "user": "user",
    "password": "pass",
    "persistent": false,
    "ssl": true,
    "sslParams": {
      "ca": "/path/to/ca",
      "cert": "/path/to/cert",
      "key": "/path/to/key",
      "verify": true
    }
  }
}
EOT;

file_put_contents(
    'vfs://unitTests/ssl',
    $ssl
);
