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

namespace Celsius3\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * NewsRss controller.
 *
 * @Route("/news/feed")
 */
class NewsFeedsController extends BaseInstanceDependentController
{

    protected function getUrl(Request $request)
    {
        $domain = $request->server->get('HTTP_HOST');
        $name_file = $request->server->get('PHP_SELF');
        $language = $request->get('_locale');
        return 'http://' . $domain . $name_file . '/' . $language;
    }

    protected function getInstance()
    {
        return $this->get('celsius3_core.instance_helper')->getUrlInstance();
    }

    /**
     * Generate Rss News.
     *
     * @Route("/rss", defaults={"_format"="xml"} ,name="rss_news")
     * @Template("Celsius3CoreBundle:NewsFeeds:index_rss.html.twig")
     *
     */
    public function rssAction(Request $request)
    {
        $full_url = $this->getUrl($request);

        return [
            'instance' => $this->getInstance(),
            'lastNews' => $this->getDoctrine()->getManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNews($this->getInstance()),
            'url' => $full_url . '/newsFeeds/rss',
        ];
    }

}
