<?php

/**
 * Msgmft library.
 *
 * Copyright (c) 2015 Fabian Grutschus
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

use ArrayAccess;
use IteratorAggregate;
use Serializable;
use Countable;
use ArrayObject;

/**
 *
 * @author Fabian Grutschus <f.grutschus@lubyte.de>
 */
class TranslationCollection implements IteratorAggregate, ArrayAccess, Serializable, Countable
{

    /**
     * Translation objects.
     *
     * @var Translation[]
     */
    protected $translations = array();

    /**
     * @param Translation[] $array
     */
    public function __construct(array $array = array())
    {
        foreach ($array as $entry) {
            $this->append($entry);
        }
    }

    public function append(Translation $translation)
    {
        $this->translations[$translation->getId()] = $translation;
    }

    public function getArrayCopy()
    {
        return $this->translations;
    }

    public function count()
    {
        return count($this->translations);
    }

    public function getIterator()
    {
        return new ArrayObject($this->translations);
    }

    public function offsetExists($offset)
    {
        return isset($this->translations[$offset]);
    }

    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->translations[$offset];
        }

        return null;
    }

    public function offsetSet($offset, $value)
    {
        // we ignore the offset
        $this->append($value);
    }

    public function offsetUnset($offset)
    {
        unset($this->translations[$offset]);
    }

    public function serialize()
    {
        return serialize($this->translations);
    }

    public function unserialize($serialized)
    {
        $this->translations = unserialize($serialized);
    }
}
