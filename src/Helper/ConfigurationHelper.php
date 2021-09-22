<?php

declare(strict_types=1);

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

namespace Celsius3\Helper;

use Celsius3\CoreBundle\Entity\Configuration;
use Celsius3\CoreBundle\Validator\Constraints\EmailDomain;
use Celsius3\Form\Type\ConfirmationType;
use Celsius3\Form\Type\LanguageType;
use Celsius3\Form\Type\LogoSelectorType;
use Celsius3\Form\Type\ResultsType;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Validator\Constraints\Image;

class ConfigurationHelper
{
    public const CONF__INSTANCE_TITLE = 'instance_title';
    public const CONF__INSTANCE_TAGLINE = 'instance_tagline';
    public const CONF__INSTANCE_STAFF = 'instance_staff';
    public const CONF__RESULTS_PER_PAGE = 'results_per_page';
    public const CONF__EMAIL_REPLY_ADDRESS = 'email_reply_address';
    public const CONF__INSTANCE_DESCRIPTION = 'instance_description';
    public const CONF__INSTANCE_INFORMATION = 'instance_information';
    public const CONF__DEFAULT_LANGUAGE = 'default_language';
    public const CONF__CONFIRMATION_TYPE = 'confirmation_type';
    public const CONF__MAIL_SIGNATURE = 'mail_signature';
    public const CONF__MIN_DAYS_FOR_SEND_MAIL = 'min_days_for_send_mail';
    public const CONF__MAX_DAYS_FOR_SEND_MAIL = 'max_days_for_send_mail';
    public const CONF__INSTANCE_LOGO = 'instance_logo';
    public const CONF__INSTANCE_CSS = 'instance_css';
    public const CONF__SMTP_HOST = 'smtp_host';
    public const CONF__SMTP_PORT = 'smtp_port';
    public const CONF__SMTP_PROTOCOL = 'smtp_protocol';
    public const CONF__SMTP_USERNAME = 'smtp_username';
    public const CONF__SMTP_PASSWORD = 'smtp_password';
    public const CONF__SMTP_STATUS = 'smtp_status';
    public const CONF__DOWNLOAD_TIME = 'download_time';
    public const CONF__SHOW_NEWS = 'show_news';
    public const CONF__RESETTING_CHECK_EMAIL_TITLE = 'resetting_check_email_title';
    public const CONF__RESETTING_CHECK_EMAIL_TEXT = 'resetting_check_email_text';
    public const CONF__RESETTING_PASSWORD_ALREADY_REQUESTED_TITLE = 'resetting_password_already_requested_title';
    public const CONF__RESETTING_PASSWORD_ALREADY_REQUESTED_TEXT = 'resetting_password_already_requested_text';
    public const CONF__REGISTRATION_WAIT_CONFIRMATION_TITLE = 'registration_wait_confirmation_title';
    public const CONF__REGISTRATION_WAIT_CONFIRMATION_TEXT = 'registration_wait_confirmation_text';
    public const CONF__HOME_HOME_BTN_TEXT = 'home_home_btn_text';
    public const CONF__HOME_NEWS_BTN_TEXT = 'home_news_btn_text';
    public const CONF__HOME_INFORMATION_BTN_TEXT = 'home_information_btn_text';
    public const CONF__HOME_STATISTICS_BTN_TEXT = 'home_statistics_btn_text';
    public const CONF__HOME_HELP_BTN_TEXT = 'home_help_btn_text';
    public const CONF__HOME_NEWS_VISIBLE = 'home_news_visible';
    public const CONF__HOME_INFORMATION_VISIBLE = 'home_information_visible';
    public const CONF__HOME_STATISTICS_VISIBLE = 'home_statistics_visible';
    public const CONF__HOME_HELP_VISIBLE = 'home_help_visible';
    public const CONF__EMAIL_DOMAIN_FOR_REGISTRATION = 'email_domain_for_registration';
    public $languages = array(
        'Spanish' => 'es',
        'English' => 'en',
        'Portuguese' => 'pt',
    );
    public $confirmation = array(
        'Administrator confirmation' => 'admin',
        'Email confirmation' => 'email',
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
            'required' => false,
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
            'required' => false,
        ),
        self::CONF__INSTANCE_INFORMATION => array(
            'name' => 'Instance information',
            'value' => '',
            'type' => 'text',
            'required' => false,
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
            'required' => false,
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
            'required' => false,
        ),
        self::CONF__INSTANCE_CSS => array(
            'name' => 'Instance CSS',
            'value' => '',
            'type' => 'text',
            'required' => false,
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
        self::CONF__SMTP_PROTOCOL => array(
            'name' => 'SMTP Protocol',
            'value' => 'ssl',
            'type' => 'select',
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
        ),
        self::CONF__SMTP_STATUS => array(
            'name' => 'SMTP Status',
            'value' => true,
            'type' => 'hidden',
        ),
        self::CONF__DOWNLOAD_TIME => array(
            'name' => 'Download time in hours',
            'value' => '24',
            'type' => 'integer',
        ),
        self::CONF__SHOW_NEWS => array(
            'name' => 'Show news',
            'value' => true,
            'type' => 'boolean',
            'required' => false,
        ),
        self::CONF__RESETTING_CHECK_EMAIL_TITLE => array(
            'name' => 'Resetting title',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
        self::CONF__RESETTING_CHECK_EMAIL_TEXT => array(
            'name' => 'Resetting text',
            'value' => '',
            'type' => 'text',
            'required' => false,
        ),
        self::CONF__RESETTING_PASSWORD_ALREADY_REQUESTED_TITLE => array(
            'name' => 'Password already requested title',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
        self::CONF__RESETTING_PASSWORD_ALREADY_REQUESTED_TEXT => array(
            'name' => 'Password already requested text',
            'value' => '',
            'type' => 'text',
            'required' => false,
        ),
        self::CONF__REGISTRATION_WAIT_CONFIRMATION_TITLE => array(
            'name' => 'Wait confirmation title',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
        self::CONF__REGISTRATION_WAIT_CONFIRMATION_TEXT => array(
            'name' => 'Wait confirmation text',
            'value' => '',
            'type' => 'text',
            'required' => false,
        ),
        self::CONF__HOME_HOME_BTN_TEXT => array(
            'name' => 'Home button text',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
        self::CONF__HOME_NEWS_BTN_TEXT => array(
            'name' => 'News button text',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
        self::CONF__HOME_NEWS_VISIBLE => array(
            'name' => '',
            'value' => true,
            'type' => 'boolean',
            'required' => false,
        ),
        self::CONF__HOME_INFORMATION_BTN_TEXT => array(
            'name' => '',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
        self::CONF__HOME_INFORMATION_VISIBLE => array(
            'name' => '',
            'value' => true,
            'type' => 'boolean',
            'required' => false,
        ),
        self::CONF__HOME_STATISTICS_BTN_TEXT => array(
            'name' => '',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
        self::CONF__HOME_STATISTICS_VISIBLE => array(
            'name' => '',
            'value' => true,
            'type' => 'boolean',
            'required' => false,
        ),
        self::CONF__HOME_HELP_BTN_TEXT => array(
            'name' => '',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
        self::CONF__HOME_HELP_VISIBLE => array(
            'name' => '',
            'value' => true,
            'type' => 'boolean',
            'required' => false,
        ),
        self::CONF__EMAIL_DOMAIN_FOR_REGISTRATION => array(
            'name' => '',
            'value' => '',
            'type' => 'string',
            'required' => false,
        ),
    );
    private $equivalences = array(
        'string' => TextType::class,
        'boolean' => CheckboxType::class,
        'integer' => IntegerType::class,
        'email' => EmailType::class,
        'text' => TextareaType::class,
        'language' => LanguageType::class,
        'confirmation' => ConfirmationType::class,
        'results' => ResultsType::class,
        'file' => FileType::class,
        'password' => PasswordType::class,
        'image' => LogoSelectorType::class,
        'time' => TimeType::class,
        'select' => ChoiceType::class,
        'hidden' => HiddenType::class,
    );
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->configureConstraints();
    }

    private function configureConstraints()
    {
        $message = 'Invalid image size. The image must be a maximum of ' . $this->getHeight(
        ) . 'px wide x ' . $this->getWidth() . 'px high.';

        $image_constraints = new Image(
            [
                'mimeTypes' => ['image/png', 'image/jpeg'],
                'mimeTypesMessage' => 'Invalid image type. Please use only PNG or JPG images',
                'maxWidth' => $this->getWidth(),
                'maxHeight' => $this->getHeight(),
                'maxWidthMessage' => $message,
                'minWidthMessage' => $message,
                'maxHeightMessage' => $message,
                'minHeightMessage' => $message,
            ]
        );

        $this->configurations[self::CONF__INSTANCE_LOGO]['constraints'] = [$image_constraints];

        $this->configurations[self::CONF__EMAIL_DOMAIN_FOR_REGISTRATION]['constraints'] = [
            new EmailDomain([
                                'message' => $this->container->get('translator')->trans(
                                    'The domain is not valid',
                                    [],
                                    'Celsius3CoreBundle_Form'
                                ),
                            ]),
        ];
    }

    private function getHeight()
    {
        return 100;
    }

    private function getWidth()
    {
        return 200;
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
                $value = (bool)$configuration->getValue();
                break;
            case 'integer':
            case 'results':
                $value = (int)$configuration->getValue();
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
        $instances = $em->getRepository('Celsius3CoreBundle:Instance')->findAll();

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
        return (isset(
            $this->configurations[$configuration->getKey()]['constraints']
        )) ? $this->configurations[$configuration->getKey()]['constraints'] : array();
    }
}
