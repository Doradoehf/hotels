<?php

namespace MainBundle\Entity;

/**
 * Hotel
 */
class Hotel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $locations;

    /**
     * @var string
     */
    private $url;
    /**
     * Get id
     *
     * @return int
     */

    /**
     * @var integer
     */
    private  $externalId;

    /**
     * @var integer
     */
    private  $sellerNumber;

    /**
     * @var string
     */
    private $secretCode;

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Hotel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->locations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add location
     *
     * @param \MainBundle\Entity\Location $location
     *
     * @return Hotel
     */
    public function addLocation(\MainBundle\Entity\Location $location)
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * Remove location
     *
     * @param \MainBundle\Entity\Location $location
     */
    public function removeLocation(\MainBundle\Entity\Location $location)
    {
        $this->locations->removeElement($location);
    }

    /**
     * Get locations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    public function __toString() {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Hotel
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;


    /**
     * Add user
     *
     * @param \MainBundle\Entity\User $user
     *
     * @return Hotel
     */
    public function addUser(\MainBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \MainBundle\Entity\User $user
     */
    public function removeUser(\MainBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set externalId
     *
     * @param integer $externalId
     *
     * @return Hotel
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return integer
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set sellerNumber
     *
     * @param integer $sellerNumber
     *
     * @return Hotel
     */
    public function setSellerNumber($sellerNumber)
    {
        $this->sellerNumber = $sellerNumber;

        return $this;
    }

    /**
     * Get sellerNumber
     *
     * @return integer
     */
    public function getSellerNumber()
    {
        return $this->sellerNumber;
    }

    /**
     * Set secretCode
     *
     * @param string $secretCode
     *
     * @return Hotel
     */
    public function setSecretCode($secretCode)
    {
        $this->secretCode = $secretCode;

        return $this;
    }

    /**
     * Get secretCode
     *
     * @return string
     */
    public function getSecretCode()
    {
        return $this->secretCode;
    }
}
