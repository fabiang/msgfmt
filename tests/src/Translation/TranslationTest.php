<?php

/**
 * Msgmft library.
 *
 * Copyright (c) 2014 Fabian Grutschus
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * o Redistributions of source code must retain the above copyright
 *   notice, this list of conditions and the following disclaimer.
 * o Redistributions in binary form must reproduce the above copyright
 *   notice, this list of conditions and the following disclaimer in the
 *   documentation and/or other materials provided with the distribution.|
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Fabian Grutschus <f.grutschus@lubyte.de>
 */

namespace Fabiang\Msgfmt\Translation;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-01-08 at 15:04:18.
 *
 * @coversDefaultClass Fabiang\Msgfmt\Translation\Translation
 */
class TranslationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Translation
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Translation('mymsgid', array('test1', 'test2'));
    }

    /**
     * @covers ::__construct
     * @covers ::getId
     */
    public function testGetId()
    {
        $this->assertSame('mymsgid', $this->object->getId());
    }

    /**
     * @covers ::__construct
     * @covers ::getTranslations
     */
    public function testGetTranslations()
    {
        $this->assertSame(array('test1', 'test2'), $this->object->getTranslations());
    }

    /**
     * @covers ::__construct
     * @covers ::add
     * @uses Fabiang\Msgfmt\Translation\Translation::getTranslations
     */
    public function testAdd()
    {
        $this->object->add('test3');
        $this->assertSame(array('test1', 'test2', 'test3'), $this->object->getTranslations());
    }
}
