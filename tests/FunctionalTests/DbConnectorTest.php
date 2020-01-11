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

/**
 * User: Jesse Rushlow - Geeshoe Development
 * Date: 2/27/19 - 1:23 PM
 */

namespace Geeshoe\DbConnectorTest\FunctionalTests;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\DbConnector;
use Geeshoe\DbConnector\Exception\DbConnectorException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class DbConnectorTest
 *
 * @package Geeshoe\DbConnectorTest\FunctionalTests
 */
class DbConnectorTest extends TestCase
{
    /**
     * @var AbstractConfigObject
     */
    public static $config;

    /**
     * {@inheritDoc}
     *
     * @throws \Symfony\Component\Dotenv\Exception\FormatException
     * @throws \Symfony\Component\Dotenv\Exception\PathException
     */
    public static function setUpBeforeClass(): void
    {
        self::$config = new class extends AbstractConfigObject {
            protected function initialize(): void
            {
            }
        };

        $localFile = dirname(__DIR__, 2) .'/' . getenv('FUNC_TEST_ENV_FILE');

        $env = new Dotenv();
        $env->load($localFile);

        self::$config->host = getenv('GSD_DB_HOST');
        self::$config->port = getenv('GSD_DB_PORT');
        self::$config->user = getenv('GSD_DB_USER');
        self::$config->password = getenv('GSD_DB_PASSWORD');
        self::$config->persistent = false;
        self::$config->ssl = false;
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        self::$config->database = null;
        self::$config->caFile = null;
        self::$config->keyFile = null;
        self::$config->certFile = null;
        self::$config->ssl = false;
    }

    /**
     * @throws DbConnectorException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetConnectionReturnsPDOConnection(): void
    {
        $dbc = new DbConnector(self::$config);
        $connection = $dbc->getConnection();
        $this->assertInstanceOf(\PDO::class, $connection);
    }

    /**
     * @throws DbConnectorException
     */
    public function testGetConnectionAddsDatabaseParamIfProvided(): void
    {
        self::$config->database = 'UnitTestData';

        $this->expectException(DbConnectorException::class);
        $this->expectExceptionMessage(
            'Connection error: SQLSTATE[HY000] [1049] Unknown database \'UnitTestData'
        );
        $dbc = new DbConnector(self::$config);
        $dbc->getConnection();
    }

    /**
     * @return array
     */
    public function sslParamsDataProvider(): array
    {
        return [
            ['caFile'],
            ['keyFile'],
            ['certFile']
        ];
    }

    /**
     * @dataProvider sslParamsDataProvider
     *
     * @param string $paramToSet
     *
     * @throws DbConnectorException
     */
    public function testSSLTrueSetsCaFile(string $paramToSet): void
    {
        self::$config->ssl = true;

        self::$config->$paramToSet = '/path/to/nowhere';

        $this->expectExceptionMessage(
            'Connection error: SQLSTATE[HY000] [2006] MySQL server has gone away'
        );

        $dbc = new DbConnector(self::$config);
        $dbc->getConnection();
    }
}
