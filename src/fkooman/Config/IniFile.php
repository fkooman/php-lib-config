<?php

/**
 * Copyright 2015 François Kooman <fkooman@tuxed.net>.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace fkooman\Config;

use RuntimeException;

class IniFile implements ReaderInterface
{
    /** @var array */
    private $configFile;

    public function __construct($configFile)
    {
        if (!is_array($configFile)) {
            $configFile = [$configFile];
        }

        $this->configFile = $configFile;
    }

    public function readConfig()
    {
        foreach ($this->configFile as $configFile) {
            $fileContent = @file_get_contents($configFile);
            if (false !== $fileContent) {
                $configData = @parse_ini_string($fileContent, true);
                if (false === $configData) {
                    throw new RuntimeException(sprintf('unable to parse configuration file(s) "%s"', implode(',', $this->configFile)));
                }

                return $configData;
            }
        }

        throw new RuntimeException(sprintf('unable to read configuration file(s) "%s"', implode(',', $this->configFile)));
    }
}
