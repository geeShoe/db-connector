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

declare(strict_types=1);

namespace Geeshoe\DbConnector;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\Exception\DbConnectorException;

/**
 * Class DbConnector
 *
 * @package Geeshoe\DbConnector
 */
class DbConnector
{
    /**
     * @var AbstractConfigObject
     */
    protected $config;

    /**
     * @var array Attributes required at time of PDO connection creation.
     */
    public $connectionAttr = [];

    /**
     * DbConnector constructor.
     *
     * @param AbstractConfigObject $configObject
     */
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
        $this->setConnectionAttributes();

        $dsn = 'mysql:host=' . $this->config->host;

        $dsn .= ';port=' . $this->config->port;

        if (!empty($this->config->database)) {
            $dsn .= ';dbname=' . $this->config->database;
        }

        try {
            $connection = new \PDO(
                $dsn,
                $this->config->user,
                $this->config->password,
                $this->connectionAttr
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

    /**
     * Get connection attributes from the config, and add to connectionAttr array.
     */
    protected function setConnectionAttributes(): void
    {
        $this->connectionAttr[\PDO::ATTR_PERSISTENT] = $this->config->persistent;

        if ($this->config->ssl === true) {
            $this->setSSLAttributes();
        }
    }

    protected function setSSLAttributes(): void
    {
        $sslAttributes = [
            'caFile' => 'sslCAFile',
            'certFile' => 'sslCertFile',
            'keyFile' => 'sslKeyFile',
            'verifySSL' => 'sslVerify'
        ];

        foreach ($sslAttributes as $envVar => $method) {
            if (!empty($this->config->$envVar)) {
                $this->connectionAttr += ConnectionAttribute::$method($this->config->$envVar);
            }
        }
    }
}
