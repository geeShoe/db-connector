<?php
require __DIR__ . '/vendor/autoload.php';

$stream = \org\bovigo\vfs\vfsStream::setup('unitTests');
\org\bovigo\vfs\vfsStream::newFile('config1', 0000)->at($stream);
\org\bovigo\vfs\vfsStream::newFile('config2')->at($stream);
\org\bovigo\vfs\vfsStream::newFile('config3')->at($stream);
\org\bovigo\vfs\vfsStream::newFile('hostname')->at($stream);
\org\bovigo\vfs\vfsStream::newFile('port')->at($stream);
\org\bovigo\vfs\vfsStream::newFile('database')->at($stream);
\org\bovigo\vfs\vfsStream::newFile('username')->at($stream);
\org\bovigo\vfs\vfsStream::newFile('password')->at($stream);
\org\bovigo\vfs\vfsStream::newFile('persistent')->at($stream);



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
    '{"dbConnector":{"host": "host","port":12,"database":"db"}}'
);
file_put_contents(
    'vfs://unitTests/password',
    '{"dbConnector":{"host": "host","port":12,"database":"db","user":"user"}}'
);
file_put_contents(
    'vfs://unitTests/persistent',
    '{"dbConnector":{"host": "host","port":12,"database":"db","user":"user","password":"pass"}}'
);

file_put_contents(
    'vfs://unitTests/config3',
    '{"dbConnector":{"host": "host","port":12,"database":"db","user":"user","password":"pass", "persistent":true}}'
);
