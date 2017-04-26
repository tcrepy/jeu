<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Chats
 *
 * @ORM\Table(name="chats")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChatsRepository")
 */
class Chats
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Many Chats have one Parties
     * @ORM\ManyToOne(targetEntity="Parties")
     */
    private $parties;

    /**
     * Many Chats have one Users
     * @ORM\ManyToOne(targetEntity="Users")
     */

    private $users;

    /**
     * @var string
     *
     * @ORM\Column(name="chat_message", type="text")
     */
    private $chatMessage;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="chat_date", type="datetime")
     */
    private $chatDate;


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
     * Set chatMessage
     *
     * @param string $chatMessage
     *
     * @return Chats
     */
    public function setChatMessage($chatMessage)
    {
        $this->chatMessage = $chatMessage;

        return $this;
    }

    /**
     * Get chatMessage
     *
     * @return string
     */
    public function getChatMessage()
    {
        return $this->chatMessage;
    }

    /**
     * Set chatDate
     *
     * @param \DateTime $chatDate
     *
     * @return Chats
     */
    public function setChatDate($chatDate)
    {
        $this->chatDate = $chatDate;

        return $this;
    }

    /**
     * Get chatDate
     *
     * @return \DateTime
     */
    public function getChatDate()
    {
        return $this->chatDate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\Users $user
     */
    public function removeUser(\AppBundle\Entity\Users $user)
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
     * Set parties
     *
     * @param \AppBundle\Entity\Parties $parties
     *
     * @return Chats
     */
    public function setParties(\AppBundle\Entity\Parties $parties = null)
    {
        $this->parties = $parties;

        return $this;
    }

    /**
     * Get parties
     *
     * @return \AppBundle\Entity\Parties
     */
    public function getParties()
    {
        return $this->parties;
    }

    /**
     * Set users
     *
     * @param \AppBundle\Entity\Users $users
     *
     * @return Chats
     */
    public function setUsers(\AppBundle\Entity\Users $users = null)
    {
        $this->users = $users;

        return $this;
    }
}
