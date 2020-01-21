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

namespace Geeshoe\DbConnectorTest\FunctionalTests;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\DbConnector;
use Geeshoe\DbConnector\Exception\DbConnectorException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class DbConnectorTest
 *
 * @package Geeshoe\DbConnectorTest\FunctionalTests
 */
class DbConnectorTest extends TestCase
{
    /** @var MockObject&AbstractConfigObject */
    public $mockConfig;

    /**
     * {@inheritDoc}
     * @throws \ReflectionException
     */
    protected function setUp()
    {
        $this->mockConfig = $this->getMockForAbstractClass(AbstractConfigObject::class);

        $localFile = dirname(__DIR__, 2) . '/' . getenv('FUNC_TEST_ENV_FILE');

        $env = new Dotenv();
        $env->load($localFile);

        $this->mockConfig->host = getenv('GSD_DB_HOST');
        $this->mockConfig->port = getenv('GSD_DB_PORT');
        $this->mockConfig->user = getenv('GSD_DB_USER');
        $this->mockConfig->password = getenv('GSD_DB_PASSWORD');
        $this->mockConfig->persistent = false;
        $this->mockConfig->ssl = false;
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        $this->mockConfig->database = null;
        $this->mockConfig->caFile = null;
        $this->mockConfig->keyFile = null;
        $this->mockConfig->certFile = null;
        $this->mockConfig->ssl = false;
    }

    /**
     * @throws DbConnectorException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testGetConnectionReturnsPDOConnection(): void
    {
        $dbc = new DbConnector($this->mockConfig);
        $connection = $dbc->getConnection();
        $this->assertInstanceOf(\PDO::class, $connection);
    }

    /**
     * @throws DbConnectorException
     */
    public function testGetConnectionAddsDatabaseParamIfProvided(): void
    {
        $this->mockConfig->database = 'UnitTestData';

        $this->expectException(DbConnectorException::class);
        $this->expectExceptionMessage(
            'Connection error: SQLSTATE[HY000] [1049] Unknown database \'UnitTestData'
        );
        $dbc = new DbConnector($this->mockConfig);
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
        $this->mockConfig->ssl = true;

        $this->mockConfig->$paramToSet = '/path/to/nowhere';

        $this->expectExceptionMessage(
            'Connection error: SQLSTATE[HY000] [2006] MySQL server has gone away'
        );

        $dbc = new DbConnector($this->mockConfig);
        $dbc->getConnection();
    }
}
