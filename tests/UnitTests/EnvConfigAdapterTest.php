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
 * Date: 12/7/18 - 7:35 AM
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
     * Data provider for testValidateConfigObjectThrowsExceptions.
     *
     * @return array
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
}
