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

namespace Geeshoe\DbConnector\ConfigAdapter;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\Exception\DbConnectorException;

/**
 * Class ArrayConfigAdapter
 *
 * @package Geeshoe\DbConnector\ConfigAdapter
 */
class ArrayConfigAdapter extends AbstractConfigObject
{
    /**
     * @var array
     */
    public $configArray;

    /**
     * ArrayConfigAdapter constructor.
     *
     * @param array $configArray
     */
    public function __construct(array $configArray)
    {
        $this->configArray = $configArray;
    }

    /**
     * {@inheritDoc}
     *
     * @throws DbConnectorException
     */
    public function initialize(): void
    {
        $this->loopCheckRequiredConfigArrayKeys();

        $this->host = filter_var($this->configArray['host'], FILTER_SANITIZE_URL);
        $this->port = (int) filter_var($this->configArray['port'], FILTER_VALIDATE_INT);
        $this->user = filter_var($this->configArray['user'], FILTER_SANITIZE_STRING);
        $this->password = filter_var($this->configArray['password'], FILTER_SANITIZE_STRING);
        $this->persistent = filter_var($this->configArray['persistent'], FILTER_VALIDATE_BOOLEAN);
        $this->ssl = filter_var($this->configArray['ssl'], FILTER_VALIDATE_BOOLEAN);

        if (!empty($this->configArray['database'])) {
            $this->database = filter_var($this->configArray['database'], FILTER_SANITIZE_STRING);
        }

        if ($this->ssl === true) {
            $this->setSSLAttributes();
        }
    }

    /**
     * If an ConfigArray key is not set, throw an exception.
     *
     * The 'database' key is not checked, as it's
     * not required by db-connector.
     *
     * @param string $configArrayKey
     *
     * @throws DbConnectorException
     */
    protected function checkIfConfigArrayKeyIsSet(string $configArrayKey): void
    {
        if (!array_key_exists($configArrayKey, $this->configArray)) {
            throw new DbConnectorException(
                'DbConnector requires ' . $configArrayKey . ' to be set in the environment.',
                1004
            );
        }
    }

    /**
     * @throws DbConnectorException
     */
    protected function loopCheckRequiredConfigArrayKeys(): void
    {
        $arrayKeys = [
            'host',
            'port',
            'user',
            'password',
            'persistent',
            'ssl'
        ];

        foreach ($arrayKeys as $key) {
            $this->checkIfConfigArrayKeyIsSet($key);
        }
    }

    /**
     * Get SSL Attributes from the ConfigArray and set them in the config.
     */
    public function setSSLAttributes(): void
    {
        $attributes = [
            'CA' => filter_var($this->configArray['ca'], FILTER_SANITIZE_URL),
            'CERT' => filter_var($this->configArray['cert'], FILTER_SANITIZE_URL),
            'KEY' => filter_var($this->configArray['key'], FILTER_SANITIZE_URL),
            'VERIFY' => filter_var($this->configArray['verify'], FILTER_VALIDATE_BOOLEAN)
        ];

        if (!empty($attributes['CA'])) {
            $this->caFile = $attributes['CA'];
        }

        if (!empty($attributes['CERT'])) {
            $this->certFile = $attributes['CERT'];
        }

        if (!empty($attributes['KEY'])) {
            $this->keyFile = $attributes['KEY'];
        }

        if (!empty($attributes['VERIFY'])) {
            $this->verifySSL = $attributes['VERIFY'];
        }
    }
}
