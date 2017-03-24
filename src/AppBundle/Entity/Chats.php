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
     * Many Chats have many Users
     * @ORM\ManyToMany(targetEntity="Users", mappedBy="chats")
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
     * Add user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return Chats
     */
    public function addUser(\AppBundle\Entity\Users $user)
    {
        $this->users[] = $user;

        return $this;
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
}
