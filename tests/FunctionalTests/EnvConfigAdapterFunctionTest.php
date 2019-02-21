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
 * Date: 12/7/18 - 8:24 AM
 */

namespace Geeshoe\DbConnectorTest\FunctionalTests;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\ConfigAdapter\EnvConfigAdapter;
use Geeshoe\DbConnector\DbConnector;
use Geeshoe\DbConnector\Exception\DbConnectorException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;

/**
 * Class EnvConfigAdapterFunctionTest
 *
 * @package Geeshoe\DbConnectorTest\FunctionalTests
 */
class EnvConfigAdapterFunctionTest extends TestCase
{
    /**
     * @var AbstractConfigObject
     */
    protected $config;

    /**
     * @throws DbConnectorException
     */
    public function setUp()
    {
        $dotEnv = new Dotenv();

        try {
            $dotEnv->overload(\dirname(__DIR__, 2) . '/.env.test.local');
        } catch (\Exception $exception) {
            throw new \RuntimeException(
                'EnvConfigAdapterFunctionTest requires a .env.test.local config file to be set.',
                0,
                $exception
            );
        }

        $env = new EnvConfigAdapter();
        $env->initialize();
        $this->config = $env->getParams();
    }

    /**
     * Unset the env var's required to create a connection.
     *
     * {@inheritdoc}
     */
    public function tearDown()
    {
        putenv('GSD_DB_HOST');
        putenv('GSD_DB_PORT');
        putenv('GSD_DB_USER');
        putenv('GSD_DB_DATABASE');
        putenv('GSD_DB_PASSWORD');
        putenv('GSD_DB_PERSISTENT');
        putenv('GSD_DB_SSL');
        putenv('GSD_DB_CA');
        putenv('GSD_DB_CERT');
        putenv('GSD_DB_KEY');
        putenv('GSD_DB_VERIFY');
    }

    /**
     * @throws DbConnectorException
     */
    public function testGetConnectionsReturnsValidPDOConnection(): void
    {
//      Catch the DbConnector exception as there are no SSL Certificates
        $this->expectException(DbConnectorException::class);
        $this->expectExceptionMessage('Connection error: SQLSTATE[HY000] [2006] MySQL server has gone away');

        $dbc = new DbConnector($this->config);
        $connection = $dbc->getConnection();

        /** @noinspection UnnecessaryAssertionInspection */
        $this->assertInstanceOf(\PDO::class, $connection);

        $dbc = null;
        $connection = null;
    }
}
