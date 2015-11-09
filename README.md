[![Build Status](https://travis-ci.org/fkooman/php-lib-config.svg?branch=master)](https://travis-ci.org/fkooman/php-lib-config)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fkooman/php-lib-config/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fkooman/php-lib-config/?branch=master)

# Introduction
Simple library for reading and writing configuration files.

Supported formats:
* INI
* YAML

# Use
You can include the library using composer by requiring `fkooman/config` in 
your `composer.json`.

# API
You can initialize the `Reader` object like this:

    $reader = new Reader(
        IniReader::fromFile('config.ini')
    );

Or for YAML:

    $reader = new Reader(
        YamlReader::fromFile('config.yaml')
    );

# Examples
Imagine the following INI file:

    foo = bar
    [one]
    xyz = abc

    ; comment
    [two]
    bar = foo

    list[] = one
    list[] = "two"
    list[] = 'three'

The following calls will provide the results mentioned in the comment:

    $reader->v('foo')         // returns 'bar'
    $reader->v('one', 'xyz')  // returns 'abc'
    $reader->v('two', 'list') // returns array('one', 'two', 'three')

The second last and last parameter can be used to specify whether or not the
config value is required, and if not what the default value is. By default
the key must exist otherwise the `RuntimeException` is thrown.

    $reader->v('def', false)                      // returns null
    $reader->v('abc', 'def', 'ghi', false, 'foo') // returns 'foo'
    
# License
Licensed under the Apache License, Version 2.0;

   http://www.apache.org/licenses/LICENSE-2.0
