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
 * Date: 12/6/18 - 2:19 PM
 */
declare(strict_types=1);

namespace Geeshoe\DbConnector\ConfigAdapter;

use Geeshoe\DbConnector\Config\AbstractConfigObject;
use Geeshoe\DbConnector\Exception\DbConnectorException;

/**
 * Class EnvConfigAdapter
 *
 * @package Geeshoe\DbConnector\ConfigAdapter
 */
class EnvConfigAdapter extends AbstractConfigObject
{
    /**
     * If an environment var is not set, throw an exception.
     *
     * The GSD_DB_DATABASE env var is not checked, as it's
     * not required by db-connector.
     *
     * @param string $envVar
     *
     * @throws DbConnectorException
     */
    protected function checkIfEnvVarIsSet(string $envVar): void
    {
        if (getenv($envVar) === false) {
            throw new DbConnectorException(
                'DbConnector requires ' . $envVar . ' to be set in the environment.',
                1004
            );
        }
    }

    /**
     * @throws DbConnectorException
     */
    protected function loopCheckRequiredEnvVars(): void
    {
        $envVars = [
            'GSD_DB_HOST',
            'GSD_DB_PORT',
            'GSD_DB_USER',
            'GSD_DB_PASSWORD',
            'GSD_DB_PERSISTENT'
        ];

        foreach ($envVars as $var) {
            $this->checkIfEnvVarIsSet($var);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws DbConnectorException
     */
    public function initialize(): void
    {
        $this->loopCheckRequiredEnvVars();

        $this->host = filter_var(getenv('GSD_DB_HOST'), FILTER_SANITIZE_URL);
        $this->port = (int) filter_var(getenv('GSD_DB_PORT'), FILTER_VALIDATE_INT);
        $this->database = filter_var(getenv('GSD_DB_DATABASE'), FILTER_SANITIZE_STRING);
        $this->user = filter_var(getenv('GSD_DB_USER'), FILTER_SANITIZE_STRING);
        $this->password = filter_var(getenv('GSD_DB_PASSWORD'), FILTER_SANITIZE_STRING);
        $this->persistent = filter_var(getenv('GSD_DB_PERSISTENT'), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @inheritdoc
     */
    public function getParams(): AbstractConfigObject
    {
        return parent::getParams();
    }
}
