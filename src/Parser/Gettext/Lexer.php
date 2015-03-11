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

namespace Fabiang\Msgfmt\Parser\Gettext;

use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\TokenInterface;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Comment;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\ExtractedComment;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Flag;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Msgid;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\MsgidPlural;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Msgstr;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\MsgstrPlural;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\PreviousTranslated;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Reference;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Text;

/**
 *
 *
 * @author Fabian Grutschus <f.grutschus@lubyte.de>
 */
class Lexer implements LexerInterface
{

    /**
     * Input data.
     *
     * @var array
     */
    protected $input;

    /**
     * Cached current line
     *
     * @var string
     */
    protected $line;

    /**
     * Current line number
     *
     * @var integer
     */
    protected $lineno = 1;

    /**
     * supported token types.
     *
     * Mapping from type to token class.
     *
     * @var array
     */
    protected $supportedTypes = array(
        'Comment'            => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Comment',
        'ExtractedComment'   => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\ExtractedComment',
        'Flag'               => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Flag',
        'Msgid'              => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Msgid',
        'MsgidPlural'        => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\MsgidPlural',
        'Msgstr'             => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Msgstr',
        'MsgstrPlural'       => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\MsgstrPlural',
        'PreviousTranslated' => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\PreviousTranslated',
        'Reference'          => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Reference',
        'Text'               => 'Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Text',
    );

    /**
     * Constructor.
     *
     * @param string $input
     */
    public function __construct($input = null)
    {
        if (!empty($input)) {
            $this->setInput($input);
        }
    }

    /**
     * Set input data.
     *
     * @param string $input
     */
    public function setInput($input)
    {
        // we normalize the line breaks, explode the string by the line break and remove empty lines
        // but we maintain the array index, since they match the line numbers
        $this->input  = array_filter(explode("\n", str_replace(array("\r\n", "\r"), "\n", $input)));
        $this->line   = null;
        $this->lineno = 1;
    }

    /**
     * {@inheritDoc}
     */
    public function getAdvancedToken()
    {
        return $this->getNextToken();
    }

    /**
     * Scan for next token.
     *
     * @return TokenInterface|null
     */
    protected function getNextToken()
    {
        $scanners = array(
            'scanText',
            'scanId',
            'scanIdPlural',
            'scanString',
            'scanStringPlural',
            'scanExtractedComment',
            'scanReference',
            'scanFlag',
            'scanPreviousTranslated',
            'scanTranslatorComment',
        );

        $this->nextLine();
        foreach ($scanners as $scanner) {
            $token = call_user_func(array($this, $scanner));

            if ($token instanceof TokenInterface) {
                return $token;
            }
        }

        return null;
    }

    /**
     * Scan for text on single lines.
     *
     * @return Token|null
     */
    protected function scanText()
    {
        return $this->scan('Text', '/^"(.+)"$/');
    }

    /**
     * Scan for translation id.
     *
     * @return Token|null
     */
    protected function scanId()
    {
        return $this->scanObject('msgid', 'Msgid');
    }

    /**
     * Scan for translation id for plural translations.
     *
     * @return Token|null
     */
    protected function scanIdPlural()
    {
        return $this->scanObject('msgid_plural', 'MsgidPlural');
    }

    /**
     * Scan for translation strings for plural translations.
     *
     * @return Token|null
     */
    protected function scanStringPlural()
    {
        return $this->scan('MsgstrPlural', '/^msgstr\[\d+\] "(.+)"$/');
    }

    /**
     * Scan for translation string.
     *
     * @return Token|null
     */
    protected function scanString()
    {
        return $this->scanObject('msgstr', 'Msgstr');
    }

    /**
     * Scan for an extraced comment.
     *
     * @return Token|null
     */
    protected function scanExtractedComment()
    {
        return $this->scan('ExtractedComment', '/^#\.\s*(.+)\s*$/');
    }

    /**
     * Scan for a reference comment.
     *
     * @return Token|null
     */
    protected function scanReference()
    {
        return $this->scan('Reference', '/^#:\s*(.+)\s*$/');
    }

    /**
     * Scan for a flag comment.
     *
     * @return Token|null
     */
    protected function scanFlag()
    {
        return $this->scan('Flag', '/^#,\s*(.+)\s*$/');
    }

    /**
     * Scan for a previous translated comment.
     *
     * @return Token|null
     */
    protected function scanPreviousTranslated()
    {
        return $this->scan('PreviousTranslated', '/^#\|\s*(.+)\s*$/');
    }

    /**
     * scan for a translator comment.
     *
     * @return Token|null
     */
    protected function scanTranslatorComment()
    {
        return $this->scan('Comment', '/^#\s*(.+)\s*$/');
    }

    /**
     * Scan for an object.
     *
     * Generic method for scanning for objects.
     * The object must have the following form:
     * <code>
     * type "string"
     * </code>
     *
     * @param string $string
     * @param string $type
     * @return Token|null
     */
    protected function scanObject($string, $type)
    {
        return $this->scan($type, "/^$string \"(.+)\"$/");
    }

    /**
     * Scan for a pattern.
     *
     * @param string $type    Token type
     * @param string $pattern Patter for matching
     * @return Token|null
     */
    protected function scan($type, $pattern)
    {
        $matches = array();
        if (preg_match($pattern, $this->line, $matches)) {
            return $this->takeToken($type, $matches[1]);
        }

        return null;
    }

    /**
     * Construct token with specified parameters.
     *
     * @param string $type token type
     * @param string $value token value
     * @return TokenInterface
     */
    public function takeToken($type, $value = null)
    {
        if (isset($this->supportedTypes[$type])) {
            $tokenClass = $this->supportedTypes[$type];
            $token = new $tokenClass($type, $this->lineno, $value);
        } else {
            $token = new Token($type, $this->lineno, $value);
        }

        return $token;
    }

    /**
     * Get current line.
     *
     * Also sets line number and move array pointer to next entry.
     *
     * @return string
     */
    protected function nextLine()
    {
        $this->lineno = key($this->input) + 1;
        $this->line   = current($this->input);
        next($this->input);
        return $this->line;
    }

    /**
     * {@inheritDoc}
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * {@inheritDoc}
     */
    public function getLineNumber()
    {
        return $this->lineno;
    }

    /**
     * Get normalized input data.
     *
     * Normalization means without empty lines and split into and array.
     *
     * @return array
     */
    public function getNormalizedInput()
    {
        return $this->input;
    }
}
