<?php

namespace Celsius3\CoreBundle\Helper;

use Celsius3\CoreBundle\Document\Configuration;

class ConfigurationHelper
{

    private $equivalences = array(
        'string' => 'text',
        'boolean' => 'checkbox',
        'integer' => 'integer',
        'email' => 'email',
        'text' => 'genemu_tinymce',
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

    public function getCastedValue($configuration)
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
