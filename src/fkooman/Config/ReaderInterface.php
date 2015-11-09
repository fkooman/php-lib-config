<?php

namespace fkooman\Config;

interface ReaderInterface
{
    /**
     * Get the configuration from a particular reader as nested array.
     *
     * @return array the configuration as a nested array
     */
    public function getConfig();
}
