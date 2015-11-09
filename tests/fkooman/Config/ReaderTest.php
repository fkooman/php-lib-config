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

use PHPUnit_Framework_TestCase;

class ReaderTest extends PHPUnit_Framework_TestCase
{
    /** @var \fkooman\Ini\Reader */
    private $reader;

    public function setUp()
    {
        $this->reader = new Reader(
            new ArrayReader(
                array(
                    'foo' => 'bar',
                    'one' => array(
                        'xyz' => 'abc',
                    ),
                    'two' => array(
                        'list' => array(
                            'one', 'two', 'three',
                        ),
                    ),
                )
            )
        );
    }
    public function testRootLevel()
    {
        $this->assertEquals('bar', $this->reader->v('foo'));
    }

    public function testSubLevel()
    {
        $this->assertEquals('abc', $this->reader->v('one', 'xyz'));
    }

    public function testSubLevelArray()
    {
        $this->assertEquals(array('one', 'two', 'three'), $this->reader->v('two', 'list'));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage configuration value not found
     */
    public function testMissingValueRequired()
    {
        $this->reader->v('one', 'two', 'three');
    }

    public function testMissingValueNotRequiredNullDefault()
    {
        $this->assertNull($this->reader->v('one', 'two', 'three', false));
    }

    public function testMissingValueNotRequiredWithDefault()
    {
        $this->assertEquals('foobar', $this->reader->v('one', 'two', 'three', false, 'foobar'));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage configuration value not found
     */
    public function testDeeperLevelNotExisting()
    {
        $this->reader->v('foo', 'bar');
    }

    public function testDeeperLevelNotExistingWithDefaultValue()
    {
        $this->assertEquals('xyz', $this->reader->v('foo', 'bar', false, 'xyz'));
    }

    public function testKeyedSection()
    {
        $this->assertEquals(array('xyz' => 'abc'), $this->reader->v('one'));
    }

    public function testExplicitRequired()
    {
        $this->assertEquals('abc', $this->reader->v('one', 'xyz', true));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage configuration value not found
     */
    public function testExplicitRequiredMissing()
    {
        $this->reader->v('one', 'two', 'three', true);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage configuration value not found
     */
    public function testExplicitRequiredMissingWithDefaultValue()
    {
        $this->reader->v('one', 'two', 'three', true, 'useless');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage no configuration field requested
     */
    public function testNoParameters()
    {
        $this->reader->v();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage first argument must be string
     */
    public function testNoParametersOnlyBool()
    {
        $this->reader->v(true);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage invalid argument type
     */
    public function testNonStringParameter()
    {
        $this->reader->v('one', 'two', 5, false, 'foobar');
    }

    public function testDefaultValue()
    {
        $this->assertSame('xyz', Reader::defaultValue(array('foo', 'bar', false, 'xyz')));
        $this->assertSame(5, Reader::defaultValue(array('foo', 'bar', false, 5)));
    }

    public function testIsRequired()
    {
        $this->assertTrue(Reader::isRequired(array('foo')));
        $this->assertTrue(Reader::isRequired(array('foo', true)));
        $this->assertTrue(Reader::isRequired(array('foo', 'bar')));
        $this->assertFalse(Reader::isRequired(array('foo', false)));
        $this->assertFalse(Reader::isRequired(array('foo', false, 'def')));
    }

    public function testConfigValues()
    {
        $this->assertSame(array('foo'), Reader::configValues(array('foo', false, 'bar')));
        $this->assertSame(array('foo'), Reader::configValues(array('foo')));
        $this->assertSame(array('foo'), Reader::configValues(array('foo', true)));
    }
}
