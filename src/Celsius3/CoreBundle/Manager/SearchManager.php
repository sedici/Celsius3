<?php

namespace Celsius3\CoreBundle\Manager;
use Doctrine\ODM\MongoDB\DocumentManager;
use Celsius3\CoreBundle\Document\Instance;

class SearchManager
{
    private $dm;
    private $tokenList = array('user:' => 'BaseUser',);

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    /**
     * Detects if any token from $tokenList is present in $keyword
     *
     * @param String $keyword The raw string to parse
     * @return array
     */
    private function parseTokens($keyword)
    {
        $where = array();
        foreach ($this->tokenList as $token => $repository) {
            if (preg_match('/\b' . $token . ' \b/i', $keyword)) {
                $where[$repository] = trim(str_replace($token, '', $keyword));
            }
        }
        return $where;
    }

    /**
     * Performs the search on the specified repository
     *
     * @param string $repository
     * @param string $keyword
     * @param Instance $instance
     * @return array
     */
    public function search($repository, $keyword, Instance $instance = null)
    {
        return $this->dm->getRepository('Celsius3CoreBundle:' . $repository)
                ->findByTerm($keyword, $instance, $this->parseTokens($keyword));
    }
}
