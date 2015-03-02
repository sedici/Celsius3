<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Celsius3\CoreBundle\Helper;

use Celsius3\CoreBundle\Entity\Configuration;

class ConfigurationHelper
{
    
    const CONF__INSTANCE_TITLE = 'instance_title';
    const CONF__RESULTS_PER_PAGE = 'results_per_page';
    const CONF__EMAIL_REPLY_ADDRESS = 'email_reply_address';
    const CONF__INSTANCE_DESCRIPTION = 'instance_description';
    const CONF__DEFAULT_LANGUAGE = 'default_language';
    const CONF__CONFIRMATION_TYPE = 'confirmation_type';
    const CONF__MAIL_SIGNATURE = 'mail_signature';
    const CONF__API_KEY = 'api_key';
    
    private $equivalences = array(
        'string' => 'text',
        'boolean' => 'checkbox',
        'integer' => 'integer',
        'email' => 'email',
        'text' => 'textarea',
        'language' => 'celsius3_corebundle_language_type',
        'confirmation' => 'celsius3_corebundle_confirmation_type',
    );
    public $languages = array(
        'es' => 'Spanish',
        'en' => 'English',
        'pt' => 'Portuguese',
    );
    public $confirmation = array(
        'admin' => 'Administrator confirmation',
        'email' => 'Email confirmation',
    );

    public function guessConfigurationType(Configuration $configuration)
    {
        return (array_key_exists($configuration->getType(), $this->equivalences)) ? $this
                ->equivalences[$configuration->getType()] : 'text';
    }

    public function getCastedValue(Configuration $configuration)
    {
        $value = null;

        switch ($configuration->getType()) {
            case 'boolean':
                $value = (boolean) $configuration->getValue();
                break;
            case 'integer':
                $value = (integer) $configuration->getValue();
                break;
            default:
                $value = $configuration->getValue();
        }

        return $value;
    }

    public function duplicate(Configuration $original)
    {
        $new = new Configuration();
        $new->setName($original->getName());
        $new->setKey($original->getKey());
        $new->setValue($original->getValue());
        $new->setType($original->getType());

        return $new;
    }
}
