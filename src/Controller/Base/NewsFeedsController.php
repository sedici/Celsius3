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

namespace Celsius3\Controller\Base;

use Celsius3\Controller\Core\EntityController;
use Celsius3\Entity\News;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/news/feed', name: 'news_feeds')]
class NewsFeedsController extends EntityController
{

    public function initialize(): void
    {
        $this->setEntity(News::class);

        parent::initialize();

        $this->htmlRenderer->setTemplatePrefix('NewsFeeds/');
        $this->setInstanceDependent(true);

        $this->setSortDefaults([
            'defaultSortFieldName' => 'e.updatedAt',
            'defaultSortDirection' => 'asc',
        ]);
    }


    protected function getUrl(Request $request): string
    {
        $domain = $request->server->get('HTTP_HOST');
        $name_file = $request->server->get('PHP_SELF');
        $language = $request->get('_locale');
        return (string) 'http://' . $domain . $name_file . '/' . $language;
    }


    #[Route('/rss', defaults: ['_format' => 'xml'], name: 'rss_news')]
    public function rss(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $fullUrl = $this->getUrl($request);

        return $this->htmlRenderer->render(
            'index_rss',
            [
                'instance' => $this->instance,
                'lastNews' => $this->repository
                    ->findLastNews($this->instance),
                'url' => (string) $fullUrl . '/newsFeeds/rss',
            ]
        );
    }
}
