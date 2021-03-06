<?php

/**
 * Copyright 2016 François Kooman <fkooman@tuxed.net>.
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

use PHPUnit_Framework_TestCase;

class IniFileTest extends PHPUnit_Framework_TestCase
{
    public function testReadConfig()
    {
        $iniFile = new IniFile(__DIR__.'/test.ini');
        $this->assertSame(
            array(
                'foo' => 'bar',
                'one' => array(
                    'xyz' => 'abc',
                ),
                'two' => array(
                    'bar' => 'foo',
                    'list' => array(
                        'one',
                        'two',
                        'three',
                    ),
                ),
            ),
            $iniFile->readConfig()
        );
    }

    public function testReadMultiConfig()
    {
        $iniFile = new IniFile(
            [
                __DIR__.'/test_missing.ini',
                __DIR__.'/test.ini',
            ]
        );

        $this->assertSame(
            array(
                'foo' => 'bar',
                'one' => array(
                    'xyz' => 'abc',
                ),
                'two' => array(
                    'bar' => 'foo',
                    'list' => array(
                        'one',
                        'two',
                        'three',
                    ),
                ),
            ),
            $iniFile->readConfig()
        );
    }

    public function testReadMultiConfigExists()
    {
        $iniFile = new IniFile(
            [
                __DIR__.'/test_2.ini',
                __DIR__.'/test.ini',
            ]
        );

        $this->assertSame(
            array(
                'foo' => 'baz',
                'one' => array(
                    'xyz' => 'abc',
                ),
                'two' => array(
                    'bar' => 'foo',
                    'list' => array(
                        'one',
                        'two',
                        'three',
                    ),
                ),
            ),
            $iniFile->readConfig()
        );
    }

    /**
     * @expectedException RuntimeException
     */
    public function testReadConfigFail()
    {
        $iniFile = new IniFile(__DIR__.'/test_missing.ini');
        $iniFile->readConfig();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testReadAllConfigFail()
    {
        $iniFile = new IniFile(
            [
                __DIR__.'/test_missing.ini',
                __DIR__.'/test_missing_2.ini',
            ]
        );
        $iniFile->readConfig();
    }
}
