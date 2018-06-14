<?php

namespace MainBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;

class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $hotels;

    /**
     * @var
     */
    private $role;


    /**
     * Add hotel
     *
     * @param \MainBundle\Entity\Hotel $hotel
     *
     * @return User
     */
    public function addHotel(\MainBundle\Entity\Hotel $hotel)
    {
        $this->hotels[] = $hotel;

        return $this;
    }

    /**
     * Remove hotel
     *
     * @param \MainBundle\Entity\Hotel $hotel
     */
    public function removeHotel(\MainBundle\Entity\Hotel $hotel)
    {
        $this->hotels->removeElement($hotel);
    }

    /**
     * Get hotels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHotels()
    {
        return $this->hotels;
    }

    public function earseRoles()
    {
        $this->roles = [];

        return $this;
    }
}
