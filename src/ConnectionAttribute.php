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

declare(strict_types=1);

namespace Geeshoe\DbConnector;

/**
 * Class ConnectionAttribute
 *
 * @package Geeshoe\DbConnector
 */
class ConnectionAttribute
{
    /**
     * @param string $caFile
     * @return array
     */
    public static function sslCAFile(string $caFile): array
    {
        return [\PDO::MYSQL_ATTR_SSL_CA => $caFile];
    }

    /**
     * @param string $certFile
     * @return array
     */
    public static function sslCertFile(string $certFile): array
    {
        return [\PDO::MYSQL_ATTR_SSL_CERT => $certFile];
    }

    /**
     * @param string $keyFile
     * @return array
     */
    public static function sslKeyFile(string $keyFile): array
    {
        return [\PDO::MYSQL_ATTR_SSL_KEY => $keyFile];
    }

    /**
     * @param bool $bool
     * @return array
     */
    public static function sslVerify(bool $bool): array
    {
        return [\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => $bool];
    }
}
