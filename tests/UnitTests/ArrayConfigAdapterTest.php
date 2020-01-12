<?php

/**
 * Copyright 2019 Jesse Rushlow - Geeshoe Development
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

namespace Geeshoe\DbConnectorTest\UnitTests;

use Geeshoe\DbConnector\ConfigAdapter\ArrayConfigAdapter;
use Geeshoe\DbConnector\Exception\DbConnectorException;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayConfigAdapterTest
 *
 * @package Geeshoe\DbConnectorTest\UnitTests
 */
class ArrayConfigAdapterTest extends TestCase
{
    /**
     * @var array
     */
    public $config = [
        'host' => '127.0.0.1',
        'port' => 1234,
        'user' => 'unit',
        'password' => 'test',
        'persistent' => true,
        'ssl' => false
    ];

    /**
     * @throws DbConnectorException
     */
    public function testCheckIfConfigArrayKeyIsSetThrowsException(): void
    {
        $adapter = new ArrayConfigAdapter([]);

        $this->expectException(DbConnectorException::class);

        $adapter->initialize();
    }

    /**
     * @return array
     */
    public function arrayKeysDataProvider(): array
    {
        return [
            'Host' => [['port' => '', 'user' => '', 'password' => '', 'persistent' => '', 'ssl' => ''], 'host'],
            'Port' => [['host' => '', 'user' => '', 'password' => '', 'persistent' => '', 'ssl' => ''], 'port'],
            'Username' => [['host' => '', 'port' => '', 'password' => '', 'persistent' => '', 'ssl' => ''], 'user'],
            'Password' => [['host' => '', 'port' => '', 'user' => '', 'persistent' => '', 'ssl' => ''], 'password'],
            'Persistent' => [['host' => '', 'port' => '', 'user' => '', 'password' => '', 'ssl' => ''], 'persistent'],
            'SSL' => [['host' => '', 'port' => '', 'user' => '', 'password' => '', 'persistent' => ''], 'ssl']
        ];
    }

    /**
     * @dataProvider arrayKeysDataProvider
     *
     * @param array  $keys
     * @param string $missing
     *
     * @throws DbConnectorException
     */
    public function testLoopCheckRequiredConfigArrayKeysThrowsException(array $keys, string $missing): void
    {
        $adapter = new ArrayConfigAdapter($keys);

        $message = 'DbConnector requires ' . $missing . ' to be set in the environment.';
        $this->expectException(DbConnectorException::class);
        $this->expectExceptionMessage($message);

        $adapter->initialize();
    }

    /**
     * @throws DbConnectorException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInitializeSetsParamsFromConfigArray(): void
    {
        $adapter = new ArrayConfigAdapter($this->config);
        $adapter->initialize();

        $this->assertSame($this->config['host'], $adapter->host);
        $this->assertSame($this->config['port'], $adapter->port);
        $this->assertSame($this->config['user'], $adapter->user);
        $this->assertSame($this->config['password'], $adapter->password);
        $this->assertSame($this->config['persistent'], $adapter->persistent);
        $this->assertSame($this->config['ssl'], $adapter->ssl);
    }

    /**
     * @throws DbConnectorException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInitializeSetsDatabaseParamIfSetInConfigArray(): void
    {
        $array = $this->config;
        $array['database'] = 'data';

        $adapter = new ArrayConfigAdapter($array);
        $adapter->initialize();

        $this->assertSame('data', $adapter->database);
    }

    /**
     * @throws DbConnectorException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSetSSLAttributesSetsRequiredParamsFromConfigArray(): void
    {
        $array = $this->config;
        $array['ssl'] = true;
        $array['ca'] = '/ca/file';
        $array['cert'] = '/cert/file';
        $array['key'] = '/key/file';
        $array['verify'] = true;

        $adapter = new ArrayConfigAdapter($array);
        $adapter->initialize();

        $this->assertSame($array['ssl'], $adapter->ssl);
        $this->assertSame($array['ca'], $adapter->caFile);
        $this->assertSame($array['cert'], $adapter->certFile);
        $this->assertSame($array['key'], $adapter->keyFile);
        $this->assertSame($array['verify'], $adapter->verifySSL);
    }
}
