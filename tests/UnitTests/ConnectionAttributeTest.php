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

use Geeshoe\DbConnector\ConnectionAttribute;
use PHPUnit\Framework\TestCase;

/**
 * Class ConnectionAttributeTest
 *
 * @package Geeshoe\DbConnectorTest\UnitTests
 */
class ConnectionAttributeTest extends TestCase
{
    /**
     * @return array
     */
    public function attributeDataProvider(): array
    {
        return [
            [\PDO::MYSQL_ATTR_SSL_CA, 'sslCAFile', '/ca/file'],
            [\PDO::MYSQL_ATTR_SSL_CERT, 'sslCertFile', '/cert/file'],
            [\PDO::MYSQL_ATTR_SSL_KEY, 'sslKeyFile', '/key/file']
        ];
    }

    /**
     * @dataProvider attributeDataProvider
     *
     * @param int    $attribute
     * @param string $method
     * @param string $expected
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSSLCAFileReturnsFilePath(int $attribute, string $method, string $expected): void
    {
        $this->assertSame([$attribute => $expected], ConnectionAttribute::$method($expected));
    }

    /**
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testSSLVerifyReturnsArray(): void
    {
        $this->assertSame(
            [\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true],
            ConnectionAttribute::sslVerify(true)
        );
    }
}
