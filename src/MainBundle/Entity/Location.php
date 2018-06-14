<?php

namespace MainBundle\Entity;

/**
 * Location
 */
class Location
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $locationName;

    /**
     * @var
     */
    private $hotel;

    /**
     * @var string
     */
    private $url;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set locationName
     *
     * @param string $locationName
     *
     * @return Location
     */
    public function setLocationName($locationName)
    {
        $this->locationName = $locationName;

        return $this;
    }

    /**
     * Get locationName
     *
     * @return string
     */
    public function getLocationName()
    {
        return $this->locationName;
    }

    /**
     * Set hotel
     *
     * @param \MainBundle\Entity\Hotel $hotel
     *
     * @return Location
     */
    public function setHotel(\MainBundle\Entity\Hotel $hotel = null)
    {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * Get hotel
     *
     * @return \MainBundle\Entity\Hotel
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    public function __toString() {

        return $this->locationName;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Location
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    /**
     * @var string
     */
    private $ternimal;


    /**
     * Set ternimal
     *
     * @param string $ternimal
     *
     * @return Location
     */
    public function setTernimal($ternimal)
    {
        $this->ternimal = $ternimal;

        return $this;
    }

    /**
     * Get ternimal
     *
     * @return string
     */
    public function getTernimal()
    {
        return $this->ternimal;
    }
}
