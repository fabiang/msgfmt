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

namespace Fabiang\Msgfmt\Parser;

use Fabiang\Msgfmt\Translation\TranslationCollection;
use Fabiang\Msgfmt\Translation\Translation;
use Fabiang\Msgfmt\Parser\Gettext\LexerInterface;
use Fabiang\Msgfmt\Parser\Gettext\Lexer;
use RuntimeException;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\SupportedTokenInterface;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\Msgid;
use Fabiang\Msgfmt\Parser\Gettext\Lexer\Token\MsgidPlural;

/**
 *
 *
 * @author Fabian Grutschus <f.grutschus@lubyte.de>
 */
class Gettext implements ParserInterface
{

    protected $lexer;

    public function __construct(LexerInterface $lexer = null)
    {
        if (null === $lexer) {
            $lexer = new Lexer;
        }

        $this->lexer = $lexer;
    }

    public function parse($string)
    {
        $collection = new TranslationCollection;

        $this->lexer->setInput($string);
        $previousToken = null;
        $translation   = null;
        $msgid         = null;
        $translations  = array();
        while (null !== ($token = $this->lexer->getAdvancedToken())) {

            if ($previousToken instanceof SupportedTokenInterface) {
                $currentClass = get_class($token);

                if (!in_array($currentClass, $previousToken->getPossibleTokens())) {
                    throw new RuntimeException(sprintf(
                        'Unexpected token "%s" with value "%s" on line %d, expected one of "%s"',
                        $token->getType(),
                        $token->getValue(),
                        $token->getLine(),
                        implode(', ', $previousToken->getPossibleTokens())
                    ));
                }
            }

            if ($token instanceof Msgid) {
                if (null === $translation) {
                    $msgid = $token->getValue();
                    $translations = array();
                } else {
                    $translation = new Translation($msgid, $translations);
                    $collection->append($translation);
                    $translation = null;
                }

            }

            $previousToken = $token;
        }

        return $collection;
    }

    public function parseStream($stream)
    {

    }
}
