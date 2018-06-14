<?php

namespace MainBundle\Entity;

/**
 * Ticket
 */
class Ticket
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $task;

    /**
     * @var string
     */
    private $ticketNumber;

    /**
     * @var string
     */
    private $secretCode;

    /**
     * @var string
     */
    private $sellerNumber;

    /**
     * @var string
     */
    private $ternimalNumber;

    /**
     * @var int
     */
    private $globalLanguage;


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
     * Set task
     *
     * @param string $task
     *
     * @return Ticket
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set ticketNumber
     *
     * @param string $ticketNumber
     *
     * @return Ticket
     */
    public function setTicketNumber($ticketNumber)
    {
        $this->ticketNumber = $ticketNumber;

        return $this;
    }

    /**
     * Get ticketNumber
     *
     * @return string
     */
    public function getTicketNumber()
    {
        return $this->ticketNumber;
    }

    /**
     * Set secretCode
     *
     * @param string $secretCode
     *
     * @return Ticket
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

    /**
     * Set sellerNumber
     *
     * @param string $sellerNumber
     *
     * @return Ticket
     */
    public function setSellerNumber($sellerNumber)
    {
        $this->sellerNumber = $sellerNumber;

        return $this;
    }

    /**
     * Get sellerNumber
     *
     * @return string
     */
    public function getSellerNumber()
    {
        return $this->sellerNumber;
    }

    /**
     * Set ternimalNumber
     *
     * @param string $ternimalNumber
     *
     * @return Ticket
     */
    public function setTernimalNumber($ternimalNumber)
    {
        $this->ternimalNumber = $ternimalNumber;

        return $this;
    }

    /**
     * Get ternimalNumber
     *
     * @return string
     */
    public function getTernimalNumber()
    {
        return $this->ternimalNumber;
    }

    /**
     * Set globalLanguage
     *
     * @param integer $globalLanguage
     *
     * @return Ticket
     */
    public function setGlobalLanguage($globalLanguage)
    {
        $this->globalLanguage = $globalLanguage;

        return $this;
    }

    /**
     * Get globalLanguage
     *
     * @return int
     */
    public function getGlobalLanguage()
    {
        return $this->globalLanguage;
    }
}
