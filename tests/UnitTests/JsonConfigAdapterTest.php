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
 * Date: 11/29/18 - 4:00 PM
 */

namespace Geeshoe\DbConnectorTest\UnitTests;

use Geeshoe\DbConnector\ConfigAdapter\JsonConfigAdapter;
use Geeshoe\DbConnector\Exception\DbConnectorException;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonConfigAdapterTest
 *
 * @package Geeshoe\DbConnectorTest\UnitTests
 */
class JsonConfigAdapterTest extends TestCase
{
    /**
     * Data provider for testValidateConfigFileThrowsExceptions
     *
     * @return array
     */
    public function validateConfigFileExceptions(): array
    {
        return [
            [
                '/path/to/nowhere',
                'Specified configuration file does not exist.',
                1001
            ],
            [
                'vfs://unitTests/config1',
                'Config file is not readable by DbConnector.',
                1002
            ],
            [
                'vfs://unitTests/config2',
                'The config file is malformed. Please refer to documentation for schema information.',
                1003
            ]
        ];
    }

    /**
     * @dataProvider validateConfigFileExceptions
     *
     * @param string $filePath
     * @param string $exceptionMsg
     * @param int $exceptionCode
     *
     * @throws DbConnectorException
     */
    public function testValidateConfigFileThrowsExceptions(
        string $filePath,
        string $exceptionMsg,
        int $exceptionCode
    ): void {
        $config = new JsonConfigAdapter($filePath);
        $this->expectException(DbConnectorException::class);
        $this->expectExceptionMessage($exceptionMsg);
        $this->expectExceptionCode($exceptionCode);
        $config->initialize();
    }

    /**
     * Data provider for testValidateConfigObjectThrowsExceptions.
     *
     * @return array
     */
    public function validateConfigObjectDataProvider(): array
    {
        return [
            'Hostname' => ['host', 'vfs://unitTests/hostname'],
            'Port' => ['port', 'vfs://unitTests/port'],
            'Username' => ['user', 'vfs://unitTests/username'],
            'Password' => ['password', 'vfs://unitTests/password'],
            'Persistent' => ['persistent', 'vfs://unitTests/persistent']
        ];
    }

    /**
     * @dataProvider validateConfigObjectDataProvider
     *
     * @param string $field
     * @param string $configPath Path to config from bootstrapTests.php
     *
     * @throws DbConnectorException
     */
    public function testValidateConfigObjectThrowsExceptions(string $field, string $configPath): void
    {
        $config = new JsonConfigAdapter($configPath);
        $this->expectException(DbConnectorException::class);
        $this->expectExceptionMessage('DbConnector requires ' . $field . ' to be set in the config file.');
        $this->expectExceptionCode(1004);
        $config->initialize();
    }

    /**
     * Data provider for testGetParamsReturnsParamsFromJsonConfigFile
     *
     * @return array
     */
    public function goodParamsDataProvider(): array
    {
        return [
            ['host', 'host'],
            ['port', 12],
            ['database', 'db'],
            ['user', 'user'],
            ['password', 'pass']
        ];
    }
    /**
     * If the config file is valid, test everything works as intended.
     *
     * @dataProvider goodParamsDataProvider
     *
     * @param string $property
     * @param mixed $key
     *
     * @throws DbConnectorException
     */
    public function testGetParamsReturnsParamsFromJsonConfigFile(string $property, $key): void
    {
        $config = new JsonConfigAdapter('vfs://unitTests/config3');
        $config->initialize();
        $results = $config->getParams();

        $this->assertSame($key, $results->$property);
    }
}
