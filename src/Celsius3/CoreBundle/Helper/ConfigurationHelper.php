<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckBoxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Celsius3\CoreBundle\Form\Type\LanguageType;
use Celsius3\CoreBundle\Form\Type\ConfirmationType;
use Celsius3\CoreBundle\Form\Type\ResultsType;
use Celsius3\CoreBundle\Form\Type\LogoSelectorType;

class ConfigurationHelper
{

    const CONF__INSTANCE_TITLE = 'instance_title';
    const CONF__INSTANCE_TAGLINE = 'instance_tagline';
    const CONF__INSTANCE_STAFF = 'instance_staff';
    const CONF__RESULTS_PER_PAGE = 'results_per_page';
    const CONF__EMAIL_REPLY_ADDRESS = 'email_reply_address';
    const CONF__INSTANCE_DESCRIPTION = 'instance_description';
    const CONF__INSTANCE_INFORMATION = 'instance_information';
    const CONF__DEFAULT_LANGUAGE = 'default_language';
    const CONF__CONFIRMATION_TYPE = 'confirmation_type';
    const CONF__MAIL_SIGNATURE = 'mail_signature';
    const CONF__API_KEY = 'api_key';
    const CONF__MIN_DAYS_FOR_SEND_MAIL = 'min_days_for_send_mail';
    const CONF__MAX_DAYS_FOR_SEND_MAIL = 'max_days_for_send_mail';
    const CONF__INSTANCE_LOGO = 'instance_logo';
    const CONF__INSTANCE_CSS = 'instance_css';
    const CONF__SMTP_HOST = 'smtp_host';
    const CONF__SMTP_PORT = 'smtp_port';
    const CONF__SMTP_USERNAME = 'smtp_username';
    const CONF__SMTP_PASSWORD = 'smtp_password';

    private $equivalences = array(
        'string' => TextType::class,
        'boolean' => CheckBoxType::class,
        'integer' => IntegerType::class,
        'email' => EmailType::class,
        'text' => TextareaType::class,
        'language' => LanguageType::class,
        'confirmation' => ConfirmationType::class,
        'results' => ResultsType::class,
        'file' => FileType::class,
        'password' => PasswordType::class,
        'image' => LogoSelectorType::class,
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
    public $results = array(
        '10' => '10',
        '15' => '15',
        '25' => '25',
    );
    public $configurations = array(
        self::CONF__INSTANCE_TITLE => array(
            'name' => 'Title',
            'value' => 'Default title',
            'type' => 'string',
        ),
        self::CONF__INSTANCE_TAGLINE => array(
            'name' => 'Tagline',
            'value' => 'Instance description',
            'type' => 'string',
        ),
        self::CONF__INSTANCE_STAFF => array(
            'name' => 'Staff',
            'value' => 'Instance Staff',
            'type' => 'text',
        ),
        self::CONF__RESULTS_PER_PAGE => array(
            'name' => 'Results per page',
            'value' => '10',
            'type' => 'results',
        ),
        self::CONF__EMAIL_REPLY_ADDRESS => array(
            'name' => 'Reply to',
            'value' => 'sample@instance.edu',
            'type' => 'email',
        ),
        self::CONF__INSTANCE_DESCRIPTION => array(
            'name' => 'Instance description',
            'value' => '',
            'type' => 'text',
        ),
        self::CONF__INSTANCE_INFORMATION => array(
            'name' => 'Instance information',
            'value' => '',
            'type' => 'text',
        ),
        self::CONF__DEFAULT_LANGUAGE => array(
            'name' => 'Default language',
            'value' => 'es',
            'type' => 'language',
        ),
        self::CONF__CONFIRMATION_TYPE => array(
            'name' => 'Confirmation type',
            'value' => 'email',
            'type' => 'confirmation',
        ),
        self::CONF__MAIL_SIGNATURE => array(
            'name' => 'Mail signature',
            'value' => '',
            'type' => 'text',
        ),
        self::CONF__API_KEY => array(
            'name' => 'Api Key',
            'value' => '',
            'type' => 'string',
        ),
        self::CONF__MIN_DAYS_FOR_SEND_MAIL => array(
            'name' => 'Minimun days for send emails',
            'value' => '5',
            'type' => 'integer',
        ),
        self::CONF__MAX_DAYS_FOR_SEND_MAIL => array(
            'name' => 'Maximun days for send emails',
            'value' => '10',
            'type' => 'integer',
        ),
        self::CONF__INSTANCE_LOGO => array(
            'name' => 'Instance Logo',
            'value' => '',
            'type' => 'image',
        ),
        self::CONF__INSTANCE_CSS => array(
            'name' => 'Instance CSS',
            'value' => '',
            'type' => 'text',
        ),
        self::CONF__SMTP_HOST => array(
            'name' => 'SMTP Host',
            'value' => '',
            'type' => 'string',
        ),
        self::CONF__SMTP_PORT => array(
            'name' => 'SMTP Port',
            'value' => '',
            'type' => 'integer',
        ),
        self::CONF__SMTP_USERNAME => array(
            'name' => 'SMTP Username',
            'value' => '',
            'type' => 'string',
        ),
        self::CONF__SMTP_PASSWORD => array(
            'name' => 'SMTP Password',
            'value' => '',
            'type' => 'password',
        )
    );
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->configureConstraints();
    }

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
            case 'results':
                $value = (integer) $configuration->getValue();
                break;
            case 'file':
                $value = null;
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

    public function updateConfigurations()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $instances = $em->getRepository('Celsius3CoreBundle:Instance')
                ->findAll();

        foreach ($this->configurations as $key => $configuration) {
            foreach ($instances as $instance) {
                if (!$instance->has($key)) {
                    $new = new Configuration();
                    $new->setKey($key);
                    $new->setName($configuration['name']);
                    $new->setValue($configuration['value']);
                    $new->setType($configuration['type']);
                    $new->setInstance($instance);
                    $em->persist($new);
                } else {
                    $conf = $instance->get($key);
                    if ($conf->getType() !== $configuration['type']) {
                        $conf->setType($configuration['type']);
                        $em->persist($conf);
                    }
                    unset($conf);
                }
            }
        }
        $em->flush();
    }

    public function getConstraints(Configuration $configuration)
    {
        return (isset($this->configurations[$configuration->getKey()]['constraints'])) ? $this->configurations[$configuration->getKey()]['constraints'] : array();
    }

    private function getHeight() {
        return 100;
    }

    private function getWidth() {
        return 200;
    }

    private function configureConstraints()
    {
        $message = 'Invalid image size. Images must be '. $this->getHeight().' x '.$this->getWidth();

        $imageConstraints = new Image(
                array(
                    'mimeTypes' => array('image/png', 'image/jpeg'),
                    'mimeTypesMessage' => 'Invalid image type. Please use only PNG or JPG images',
                    'minWidth' => $this->getWidth(),
                    'maxWidth' => $this->getWidth(),
                    'minHeight' => $this->getHeight(),
                    'maxHeight' => $this->getHeight(),
                    'maxWidthMessage' => $message,
                    'minWidthMessage' => $message,
                    'maxHeightMessage' => $message,
                    'minHeightMessage' => $message,
                )
        );

        $this->configurations['instance_logo']['constraints'] = array($imageConstraints);
    }

}
