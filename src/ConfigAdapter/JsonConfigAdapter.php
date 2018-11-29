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
 * Date: 11/29/18 - 3:45 PM
 */
declare(strict_types=1);

namespace Geeshoe\DbConnector\ConfigAdapter;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\Exception\DbConnectorException;

/**
 * Class JsonConfigAdapter
 *
 * @package Geeshoe\DbConnector\ConfigAdapter
 */
class JsonConfigAdapter extends AbstractConfigObject
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var object
     */
    protected $jsonObject;

    /**
     * ConfigJsonAdapter constructor.
     *
     * @param string $jsonConfigFilePath
     */
    public function __construct(string $jsonConfigFilePath)
    {
        $this->filePath = filter_var($jsonConfigFilePath, FILTER_SANITIZE_URL);
    }

    /**
     * Verify the config file exists, is readable, and valid json.
     *
     * @throws DbConnectorException
     */
    protected function validateConfigFile(): void
    {
        switch ($this->filePath) {
            case !is_file($this->filePath):
                throw new DbConnectorException(
                    'Specified configuration file does not exist.',
                    1001
                );
                break;
            case !is_readable($this->filePath):
                throw new DbConnectorException(
                    'Config file is not readable by DbConnector.',
                    1002
                );
                break;
        }

        $jsonConfig = json_decode(file_get_contents($this->filePath));
        $this->filePath = null;

        if (empty($jsonConfig->dbConnector) || !\is_object($jsonConfig->dbConnector)) {
            throw new DbConnectorException(
                'The config file is malformed. Please refer to documentation for schema information.',
                1003
            );
        }

        $this->jsonObject = $jsonConfig->dbConnector;
    }

    /**
     * Verify that all the required database params are available.
     *
     * @throws DbConnectorException
     */
    protected function validateConfigObject(): void
    {
        $fields = ['host', 'port', 'user', 'database', 'password', 'persistent'];

        foreach ($fields as $field) {
            if (!isset($this->jsonObject->$field)) {
                throw new DbConnectorException(
                    'DbConnector requires ' . $field . ' to be set in the config file.',
                    1004
                );
            }
        }
    }

    /**
     * Initialize the Database Configuration.
     *
     * {@inheritdoc}
     *
     * @throws DbConnectorException
     */
    public function initialize(): void
    {
        $this->validateConfigFile();
        $this->validateConfigObject();

        $this->host = filter_var($this->jsonObject->host, FILTER_SANITIZE_URL);
        $this->port = (int) filter_var($this->jsonObject->port, FILTER_VALIDATE_INT);
        $this->database = filter_var($this->jsonObject->database, FILTER_SANITIZE_STRING);
        $this->user = filter_var($this->jsonObject->user, FILTER_SANITIZE_STRING);
        $this->password = filter_var($this->jsonObject->password, FILTER_SANITIZE_STRING);
        $this->persistent = filter_var($this->jsonObject->persistent, FILTER_VALIDATE_BOOLEAN);
        $this->jsonObject = null;
    }

    /**
     * {@inheritdoc}
     *
     * @return AbstractConfigObject
     */
    public function getParams(): AbstractConfigObject
    {
        return parent::getParams();
    }
}
