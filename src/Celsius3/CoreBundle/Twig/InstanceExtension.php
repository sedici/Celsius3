<?php

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Entity\MailTemplate;
use Doctrine\ORM\EntityManager;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleTest;

class InstanceExtension extends Twig_Extension
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTests()
    {
        return [
                new Twig_SimpleTest(
                        'valid_logo', function ($file) {
                    return file_exists(__DIR__.'/../../../../web/uploads/logos/'.$file);
                }
                ),
        ];
    }

    public function getFunctions()
    {
        return [
                new Twig_SimpleFunction('get_instance_url', [$this, 'getInstanceUrl']),
                new Twig_SimpleFunction('template_edited', [$this, 'templateEdited'])
        ];
    }

    public function getInstanceUrl($id)
    {
        $instance = $this->entityManager->getRepository('Celsius3CoreBundle:Instance')->find($id);

        return $instance->getUrl();
    }

    public function templateEdited(MailTemplate $template)
    {
        if ($template->getInstance() !== null && $template->getInstance()->getUrl() !== 'directory') {
            return true;
        } else {
            $templates = $this->entityManager->getRepository(MailTemplate::class)->templateEdited($template);

            if (count($templates) > 0) {
                return true;
            }

            return false;
        }
    }
}