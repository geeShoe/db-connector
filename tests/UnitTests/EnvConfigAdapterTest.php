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

namespace Geeshoe\DbConnectorTest\UnitTests;

use Geeshoe\DbConnector\ConfigAdapter\EnvConfigAdapter;
use Geeshoe\DbConnector\Exception\DbConnectorException;
use PHPUnit\Framework\TestCase;

/**
 * Class EnvConfigAdapterTest
 *
 * @package Geeshoe\DbConnectorTest\UnitTests
 */
class EnvConfigAdapterTest extends TestCase
{
    /**
     * @template TValue
     * @var array<string, TValue>
     */
    public static $envVars = [
        'GSD_DB_HOST' => '127.0.0.1',
        'GSD_DB_PORT' => 1234,
        'GSD_DB_DATABASE' => 'data',
        'GSD_DB_USER' => 'unit',
        'GSD_DB_PASSWORD' => 'test',
        'GSD_DB_PERSISTENT' => true,
        'GSD_DB_SSL' => true
    ];

    public static function tearDownAfterClass(): void
    {
        foreach (self::$envVars as $name => $var) {
            putenv("$name=");
        }
    }

    /**
     * Set the envVars for UnitTesting
     */
    public function setEnvVars(): void
    {
        foreach (self::$envVars as $name => $var) {
            putenv("$name=$var");
        }
    }

    /**
     * Data provider for testValidateConfigObjectThrowsExceptions.
     *
     * @return array<string, array<string>>
     */
    public function validateConfigObjectDataProvider(): array
    {
        return [
            'Hostname' => ['GSD_DB_HOST'],
            'Port' => ['GSD_DB_PORT'],
            'Username' => ['GSD_DB_USER'],
            'Password' => ['GSD_DB_PASSWORD'],
            'Persistent' => ['GSD_DB_PERSISTENT']
        ];
    }

    /**
     * @dataProvider validateConfigObjectDataProvider
     *
     * @param string $envVar
     *
     * @throws DbConnectorException
     */
    public function testValidateConfigEnvVarsThrowsExceptions(string $envVar): void
    {
        $this->setEnvVarsForTesting($envVar);

        $env = new EnvConfigAdapter();

        $this->expectException(DbConnectorException::class);
        $this->expectExceptionMessage('DbConnector requires ' . $envVar . ' to be set in the environment.');
        $this->expectExceptionCode(1004);

        $env->initialize();
    }

    /**
     * @param string $envVar
     */
    protected function setEnvVarsForTesting(string $envVar): void
    {
        switch ($envVar) {
            case 'GSD_DB_PORT':
                putenv('GSD_DB_HOST=');
                break;
            case 'GSD_DB_USER':
                putenv('GSD_DB_PORT=');
                break;
            case 'GSD_DB_PASSWORD':
                putenv('GSD_DB_USER=');
                break;
            case 'GSD_DB_PERSISTENT':
                putenv('GSD_DB_PASSWORD=');
                break;
        }
    }

    /**
     * @throws DbConnectorException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInitializeSetsParamsFromEnvVars(): void
    {
        $this->setEnvVars();

        $env = new EnvConfigAdapter();
        $env->initialize();

        $this->assertSame('127.0.0.1', $env->host);
        $this->assertSame(1234, $env->port);
        $this->assertSame('data', $env->database);
        $this->assertSame('unit', $env->user);
        $this->assertSame('test', $env->password);
        $this->assertTrue($env->persistent);
        $this->assertTrue($env->ssl);
    }

    /**
     * @throws DbConnectorException
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSSLTrueSetsSSLParamsFromEnv(): void
    {
        self::$envVars['GSD_DB_SSL_CA'] = '/ca/file';
        self::$envVars['GSD_DB_SSL_CERT'] = '/cert/file';
        self::$envVars['GSD_DB_SSL_KEY'] = '/key/file';
        self::$envVars['GSD_DB_SSL_VERIFY'] = true;

        $this->setEnvVars();

        $env = new EnvConfigAdapter();
        $env->initialize();

        $this->assertSame('/ca/file', $env->caFile);
        $this->assertSame('/cert/file', $env->certFile);
        $this->assertSame('/key/file', $env->keyFile);
        $this->assertTrue($env->verifySSL);
    }
}
