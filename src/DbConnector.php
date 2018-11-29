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
 * Date: 11/29/18 - 4:45 PM
 */
declare(strict_types=1);

namespace Geeshoe\DbConnector;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\Exception\DbConnectorException;

class DbConnector
{
    protected $config;

    public function __construct(AbstractConfigObject $configObject)
    {
        $this->config = $configObject;
    }

    /**
     * Create and return a PDO Connection
     *
     * @throws DbConnectorException
     *
     * @return \PDO
     */
    public function getConnection(): \PDO
    {
        $dsn = 'mysql:host=' . $this->config->host;

        if ($this->config->database !== null) {
            $dsn .= ';dbname=' . $this->config->database;
        }

        try {
            $connection = new \PDO(
                $dsn,
                $this->config->user,
                $this->config->password,
                [\PDO::ATTR_PERSISTENT => $this->config->persistent]
            );
        } catch (\PDOException $exception) {
            throw new DbConnectorException(
                'Connection error: ' . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        return $connection;
    }
}
