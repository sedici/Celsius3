<?php

namespace Celsius\Celsius3Bundle\Helper;

use Celsius\Celsius3Bundle\Document\Configuration;

class ConfigurationHelper
{

    static $equivalences = array(
        'string' => 'text',
        'boolean' => 'checkbox',
        'integer' => 'integer',
        'email' => 'email',
        'text' => 'textarea',
        'language' => 'language_type',
    );
    public static $languages = array(
        'es' => 'Spanish',
        'en' => 'English',
        'pt' => 'Portuguese',
    );

    public static function guessConfigurationType(Configuration $configuration)
    {
        return (array_key_exists($configuration->getType(), self::$equivalences)) ?
                self::$equivalences[$configuration->getType()] : 'text';
    }

    public static function getCastedValue($configuration)
    {
        $value = null;

        switch ($configuration->getType())
        {
            case 'boolean': $value = (boolean) $configuration->getValue();
                break;
            case 'integer': $value = (integer) $configuration->getValue();
                break;
            default: $value = $configuration->getValue();
        }

        return $value;
    }

}