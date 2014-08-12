<?php

/*
 * Celsius3 - Order management
 * Copyright (C) 2014 PrEBi <info@prebi.unlp.edu.ar>
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

/**
 * NewsRss controller.
 *
 * @Route("/newsFeeds")
 */
class NewsFeedsController extends BaseInstanceDependentController
{

    protected function getUrl()
    {
        $domain = $_SERVER['HTTP_HOST'];
        $name_file = $_SERVER['PHP_SELF'];
        $language = $this->get('request')->get('_locale');
        $url = "http://" . "$domain" . "$name_file" . "/$language";

        return $url;
    }

    protected function getInstance()
    {
        return $this->get('celsius3_core.instance_helper')->getUrlInstance();
    }

    /**
     * Generate Rss News.
     *
     * @Route("/rss/directory",name="directory_rss_news")
     * @Template("Celsius3CoreBundle:NewsFeeds:index_rss.html.twig")
     *
     */
    public function directory_rssAction()
    {
        $url = $this->getUrl();
        $array = array('instance' => 'Directory',
            'lastNews' => $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNewsDirectory(),
            'url' => $url . '/newsFeeds/rss/directory',);

        return $array;
    }

    /**
     * Generate Atom News.
     *
     * @Route("/atom/directory",name="directory_atom_news")
     * @Template("Celsius3CoreBundle:NewsFeeds:index_atom.html.twig")
     *
     */
    public function directory_atomAction()
    {
        $url = $this->getUrl();
        $array = array('instance' => 'Directory',
            'lastNews' => $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNewsDirectory(),
            'url' => $url . '/newsFeeds/atom/directory',);

        return $array;
    }

    /**
     * Generate Rss News.
     *
     * @Route("/rss/{urlInstance}",name="instance_rss_news")
     * @Template("Celsius3CoreBundle:NewsFeeds:index_rss.html.twig")
     *
     */
    public function instance_rssAction($urlInstance)
    {
        $url = $this->getUrl();
        $array = array('instance' => $this->getInstance(),
            'lastNews' => $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNews($this->getInstance()),
            'url' => $url . '/newsFeeds/rss/' . $urlInstance,);

        return $array;
    }

    /**
     * Generate atom News.
     *
     * @Route("/atom/{urlInstance}", name="instance_atom_news")
     * @Template("Celsius3CoreBundle:NewsFeeds:index_atom.html.twig")
     *
     */
    public function instance_atomAction($urlInstance)
    {
        $url = $this->getUrl();
        $array = array('instance' => $this->getInstance(),
            'lastNews' => $this->getDocumentManager()
                    ->getRepository('Celsius3CoreBundle:News')
                    ->findLastNews($this->getInstance()),
            'url' => $url . '/newsFeeds/atom/' . $urlInstance,);

        return $array;
    }
}