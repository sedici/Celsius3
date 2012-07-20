<?php

namespace Celsius\Celsius3Bundle\Manager;

class SearchManager
{

    protected $tokenList = array(
        'user:' => 'BaseUser',
    );

    /**
     * Detects if any token from $tokenList is present in $keyword
     * 
     * @param String $keyword The raw string to parse
     * @return array 
     */
    private function parseTokens($keyword)
    {
        $where = array();
        foreach ($this->tokenList as $token => $repository)
        {
            if (preg_match('/\b' . $token . ' \b/i', $keyword))
            {
                $where[$repository] = trim(str_replace($token, '', $keyword));
            }
        }
        return $where;
    }

    /**
     * Performs the search on the specified repository
     * 
     * @param string $keyword
     * @param DocumentManager $dm
     * @param Instance $instance
     * @return array
     */
    public function doSearch($repository, $keyword, $dm, $instance = null)
    {
        return $dm->getRepository('CelsiusCelsius3Bundle:' . $repository)
                        ->findByTerm($keyword, $instance, $this->parseTokens($keyword));
    }

}
