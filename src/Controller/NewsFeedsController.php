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

namespace Celsius3\Controller;

use Celsius3\Entity\Instance;
use Celsius3\Form\Type\InstanceType;
use Celsius3\Repository\NewsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * NewsRss controller.
 *
 * @Route("/news/feed")
 */
class NewsFeedsController extends BaseInstanceDependentController
{

    protected NewsRepository $newsRepository;

    public function __construct(
        NewsRepository $newsRepository,
        ...$args
    ) {
        parent::__construct(... $args);
        $this->newsRepository = $newsRepository;
    }

    protected final function getEntity(): string
    { return Instance::class; }

    protected final function getType(): string
    { return InstanceType::class; }

    protected final function getTemplatePrefix(): string
    { return 'NewsFeeds/'; }


    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ];
    }


    protected function getInstance(): Instance
    { return $this->instanceHelper->getUrlInstance(); }


    protected function getUrl(Request $request)
    {
        $domain = $request->server->get('HTTP_HOST');
        $name_file = $request->server->get('PHP_SELF');
        $language = $request->get('_locale');
        return (string) 'http://' . $domain . $name_file . '/' . $language;
    }


    /**
     * Generate Rss News.
     *
     * @Route("/rss", defaults={"_format"="xml"} ,name="rss_news")
     *
     */
    public function rss(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $fullUrl = $this->getUrl($request);

        return $this->render(
            (string) $this->templatePrefix . 'index_rss.html.twig',
            [
                'instance' => $this->instance,
                'lastNews' => $this->newsRepository
                    ->findLastNews($this->instance),
                'url' => (string) $fullUrl . '/newsFeeds/rss',
            ]
        );
    }
}
