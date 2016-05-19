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
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Dumper;

class YamlFile implements ReaderInterface, WriterInterface
{
    /** @var string */
    private $configFile;

    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }

    public function readConfig()
    {
        $fileContent = @file_get_contents($this->configFile);
        if (false === $fileContent) {
            throw new RuntimeException(sprintf('unable to read configuration file "%s"', $this->configFile));
        }

        return Yaml::parse($fileContent);
    }

    public function writeConfig(array $config)
    {
        $dumper = new Dumper();
        $yamlStr = $dumper->dump($config, 3);
        if (false === @file_put_contents($this->configFile, $yamlStr)) {
            throw new RuntimeException(sprintf('unable to write configuration file "%s"', $this->configFile));
        }
    }
}
