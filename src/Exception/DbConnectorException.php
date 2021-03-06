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

namespace Geeshoe\DbConnector\Exception;

use Throwable;

/**
 * Class DbConnectorException
 *
 * @package Geeshoe\DbConnector\Exception
 */
class DbConnectorException extends \Exception
{
    /**
     * DbConnectorException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     *
     * {@inheritdoc}
     */
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
