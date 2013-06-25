<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
                new Symfony\Bundle\SecurityBundle\SecurityBundle(),
                new Symfony\Bundle\TwigBundle\TwigBundle(),
                new Symfony\Bundle\MonologBundle\MonologBundle(),
                new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
                new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
                new Symfony\Bundle\AsseticBundle\AsseticBundle(),
                new JMS\AopBundle\JMSAopBundle(),
                new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
                new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
                new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
                new FOS\UserBundle\FOSUserBundle(),
                new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
                new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
                new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
                new JMS\TranslationBundle\JMSTranslationBundle(),
                new Knp\Bundle\MenuBundle\KnpMenuBundle(),
                new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
                new FOS\MessageBundle\FOSMessageBundle(),
                new Genemu\Bundle\FormBundle\GenemuFormBundle(),
                new Bc\Bundle\BootstrapBundle\BcBootstrapBundle(),
                new Celsius3\CoreBundle\Celsius3CoreBundle(),
                new Celsius3\NotificationBundle\Celsius3NotificationBundle(),
                new Celsius3\MessageBundle\Celsius3MessageBundle(),
                new Celsius3\MigrationBundle\Celsius3MigrationBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader
                ->load(
                        __DIR__ . '/config/config_' . $this->getEnvironment()
                                . '.yml');
    }

}
