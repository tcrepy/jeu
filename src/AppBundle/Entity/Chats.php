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
}
