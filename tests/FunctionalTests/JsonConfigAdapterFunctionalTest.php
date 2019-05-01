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

/**
 * User: Jesse Rushlow - Geeshoe Development
 * Date: 11/29/18 - 5:09 PM
 */

namespace Geeshoe\DbConnectorTest\FunctionalTests;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\ConfigAdapter\JsonConfigAdapter;
use Geeshoe\DbConnector\DbConnector;
use Geeshoe\DbConnector\Exception\DbConnectorException;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonConfigAdapterFunctionalTest
 *
 * @package Geeshoe\DbConnectorTest\FunctionalTests
 */
class JsonConfigAdapterFunctionalTest extends TestCase
{
    /**
     * @var AbstractConfigObject
     */
    protected $config;

    /**
     * @throws DbConnectorException
     */
    public function setUp(): void
    {
        $config = new JsonConfigAdapter(\dirname(__DIR__, 2) . '/dbConnector.json');
        $config->initialize();
        $this->config = $config->getParams();
    }

    /**
     * @throws DbConnectorException
     */
    public function testGetConnectionsReturnsValidPDOConnection(): void
    {
        $dbc = new DbConnector($this->config);
        $connection = $dbc->getConnection();
        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(\PDO::class, $connection);
    }

    /**
     * @throws DbConnectorException
     */
    public function testGetConnectionThrowsExceptionWithInvalidCredentials(): void
    {
        $this->config->user = 'badUser';
        $dbc = new DbConnector($this->config);
        $this->expectException(DbConnectorException::class);
        $this->expectExceptionMessage(
            'Connection error: SQLSTATE[HY000] [1045] Access denied for user \'badUser\'@\'localhost\' (using password: YES)'
        );
        $dbc->getConnection();
    }
}
