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

declare(strict_types=1);

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Entity\MailTemplate;
use Doctrine\ORM\EntityManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

use function count;

class InstanceExtension extends AbstractExtension
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getTests(): array
    {
        return [
            new TwigTest(
                'valid_logo',
                static function ($file) {
                    return file_exists(__DIR__.'/../../../../web/uploads/logos/'.$file);
                }
            ),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_instance_url', [$this, 'getInstanceUrl']),
            new TwigFunction('template_edited', [$this, 'templateEdited'])
        ];
    }

    public function getInstanceUrl($id): string
    {
        $instance = $this->entityManager->getRepository('Celsius3CoreBundle:Instance')->find($id);

        return $instance->getUrl();
    }

    public function templateEdited(MailTemplate $template): bool
    {
        if ($template->getInstance() !== null && $template->getInstance()->getUrl() !== 'directory') {
            return true;
        }

        $templates = $this->entityManager->getRepository(MailTemplate::class)->templateEdited($template);

        return count($templates) > 0;
    }
}
