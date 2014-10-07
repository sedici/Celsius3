<?php

namespace Celsius3\CoreBundle\Document\Analytics;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(db="celsius_analytics")
 * @ODM\Indexes({
 *   @ODM\Index(keys={"instance.id"="asc", "country.id"="asc", "providerParent.id"="asc",}),
 * })
 */
class OrderAnalytics extends Analytics
{
    /**
     * @ODM\Int
     */
    private $year;
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Country")
     */
    private $country;
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Provider")
     */
    private $providerParent;
    /**
     * @ODM\ReferenceOne(targetDocument="Celsius3\CoreBundle\Document\Provider")
     */
    private $provider;
    /**
     * Este campo contiene un array asociativo de tipos de request que a su vez
     * contiene un array asociativo de meses y por cada mes un array
     * asociativo con claves created, satisfied, cancelled, pages
     * 
     * @ODM\Field(type="collection")
     */
    private $counters;
    /**
     * Este campo contiene un array asociativo de tipos de request que a su vez
     * contiene un array asociativo de meses y por cada mes un array
     * asociativo con la cantidad de días como clave y la cantidad de pedidos
     * como valor
     * 
     * @ODM\Field(type="collection")
     */
    private $delays;

    /**
     * Set year
     *
     * @param int $year
     * @return self
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * Get year
     *
     * @return int $year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set country
     *
     * @param Celsius3\CoreBundle\Document\Country $country
     * @return self
     */
    public function setCountry(\Celsius3\CoreBundle\Document\Country $country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return Celsius3\CoreBundle\Document\Country $country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set providerParent
     *
     * @param Celsius3\CoreBundle\Document\Provider $providerParent
     * @return self
     */
    public function setProviderParent(\Celsius3\CoreBundle\Document\Provider $providerParent)
    {
        $this->providerParent = $providerParent;
        return $this;
    }

    /**
     * Get providerParent
     *
     * @return Celsius3\CoreBundle\Document\Provider $providerParent
     */
    public function getProviderParent()
    {
        return $this->providerParent;
    }

    /**
     * Set provider
     *
     * @param Celsius3\CoreBundle\Document\Provider $provider
     * @return self
     */
    public function setProvider(\Celsius3\CoreBundle\Document\Provider $provider)
    {
        $this->provider = $provider;
        return $this;
    }

    /**
     * Get provider
     *
     * @return Celsius3\CoreBundle\Document\Provider $provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Set counters
     *
     * @param collection $counters
     * @return self
     */
    public function setCounters($counters)
    {
        $this->counters = $counters;
        return $this;
    }

    /**
     * Get counters
     *
     * @return collection $counters
     */
    public function getCounters()
    {
        return $this->counters;
    }

    /**
     * Set delays
     *
     * @param collection $delays
     * @return self
     */
    public function setDelays($delays)
    {
        $this->delays = $delays;
        return $this;
    }

    /**
     * Get delays
     *
     * @return collection $delays
     */
    public function getDelays()
    {
        return $this->delays;
    }
}
