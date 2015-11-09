<?php

namespace fkooman\Config;

interface WriterInterface
{
    /**
     * Get the configuration from a particular reader as nested array.
     *
     * @return array the configuration as a nested array
     */
    public function writeConfig(array $config);
}
