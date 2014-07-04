<?php

namespace Celsius3\CoreBundle\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class CatalogResult
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     * @MongoDB\String
     */
    private $title;

    /**
     * @Assert\Type(type="integer")
     * @MongoDB\Int
     */
    private $searches = 0;

    /**
     * @Assert\Type(type="integer")
     * @MongoDB\Int
     */
    private $matches = 0;

    /**
     * @Assert\NotNull
     * @MongoDB\ReferenceOne(targetDocument="Catalog", inversedBy="positions")
     */
    private $catalog;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set searches
     *
     * @param  int  $searches
     * @return self
     */
    public function setSearches($searches)
    {
        $this->searches = $searches;

        return $this;
    }

    /**
     * Get searches
     *
     * @return int $searches
     */
    public function getSearches()
    {
        return $this->searches;
    }

    /**
     * Set matches
     *
     * @param  int  $matches
     * @return self
     */
    public function setMatches($matches)
    {
        $this->matches = $matches;

        return $this;
    }

    /**
     * Get matches
     *
     * @return int $matches
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Set catalog
     *
     * @param  Celsius3\CoreBundle\Document\Catalog $catalog
     * @return self
     */
    public function setCatalog(\Celsius3\CoreBundle\Document\Catalog $catalog)
    {
        $this->catalog = $catalog;

        return $this;
    }

    /**
     * Get catalog
     *
     * @return Celsius3\CoreBundle\Document\Catalog $catalog
     */
    public function getCatalog()
    {
        return $this->catalog;
    }

}
