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

namespace Fabiang\Msgfmt\Parser\Gettext\Lexer;

/**
 *
 *
 * @author Fabian Grutschus <f.grutschus@lubyte.de>
 */
class Token implements TokenInterface
{

    /**
     * Token type.
     *
     * @var string
     */
    protected $type;

    /**
     * Line
     *
     * @var integer
     */
    protected $line;

    /**
     * Value.
     *
     * @var string
     */
    protected $value;

    /**
     *
     * @param string  $type
     * @param integer $line
     * @param string  $value
     */
    public function __construct($type, $line, $value = null)
    {
        $this->type  = $type;
        $this->line  = (int) $line;
        $this->value = $value;
    }

    /**
     * Get token type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get line number.
     *
     * @return integer
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * Get value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
