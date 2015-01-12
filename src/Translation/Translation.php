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

/**
 *
 * @author Fabian Grutschus <f.grutschus@lubyte.de>
 */
class Translation
{

    /**
     * Translation id.
     *
     * @var string
     */
    protected $id;

    /**
     * Translations
     *
     * @var array
     */
    protected $translations = array();

    /**
     * Constructor.
     *
     * @param string $id           Translation id
     * @param array  $translations Translation strings
     */
    public function __construct($id, array $translations)
    {
        $this->id           = $id;
        $this->translations = $translations;
    }

    /**
     * Get translation id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get translation strings.
     *
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     *
     * @param string $translation
     */
    public function add($translation)
    {
        $this->translations[] = $translation;
    }
}
