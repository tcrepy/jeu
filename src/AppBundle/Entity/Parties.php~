<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parties
 *
 * @ORM\Table(name="parties")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PartiesRepository")
 */
class Parties
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Users", inversedBy="parties_1", fetch="EAGER")
     */
    private $users1;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Users", inversedBy="parties_2", fetch="EAGER")
     */
    private $users2;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Users", fetch="EAGER")
     */
    private $partieTour;

    /**
     * @var int
     *
     * @ORM\Column(name="partie_joueur_1_score", type="smallint", nullable=true)
     */
    private $partieJoueur1Score;

    /**
     * @var int
     *
     * @ORM\Column(name="partie_joueur_2_score", type="smallint", nullable=true)
     */
    private $partieJoueur2Score;


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
     * Set partieJoueur1
     *
     * @param integer $partieJoueur1
     *
     * @return Parties
     */
    public function setPartieJoueur1($partieJoueur1)
    {
        $this->partieJoueur1 = $partieJoueur1;

        return $this;
    }

    /**
     * Get partieJoueur1
     *
     * @return int
     */
    public function getPartieJoueur1()
    {
        return $this->partieJoueur1;
    }

    /**
     * Set partieJoueur1Score
     *
     * @param integer $partieJoueur1Score
     *
     * @return Parties
     */
    public function setPartieJoueur1Score($partieJoueur1Score)
    {
        $this->partieJoueur1Score = $partieJoueur1Score;

        return $this;
    }

    /**
     * Get partieJoueur1Score
     *
     * @return int
     */
    public function getPartieJoueur1Score()
    {
        return $this->partieJoueur1Score;
    }

    /**
     * Set partieJoueur2
     *
     * @param integer $partieJoueur2
     *
     * @return Parties
     */
    public function setPartieJoueur2($partieJoueur2)
    {
        $this->partieJoueur2 = $partieJoueur2;

        return $this;
    }

    /**
     * Get partieJoueur2
     *
     * @return int
     */
    public function getPartieJoueur2()
    {
        return $this->partieJoueur2;
    }

    /**
     * Set partieJoueur2Score
     *
     * @param integer $partieJoueur2Score
     *
     * @return Parties
     */
    public function setPartieJoueur2Score($partieJoueur2Score)
    {
        $this->partieJoueur2Score = $partieJoueur2Score;

        return $this;
    }

    /**
     * Get partieJoueur2Score
     *
     * @return int
     */
    public function getPartieJoueur2Score()
    {
        return $this->partieJoueur2Score;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\Users $user
     *
     * @return Parties
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users1 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users2 = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add users1
     *
     * @param \AppBundle\Entity\Parties $users1
     *
     * @return Parties
     */
    public function addUsers1(\AppBundle\Entity\Parties $users1)
    {
        $this->users1[] = $users1;

        return $this;
    }

    /**
     * Remove users1
     *
     * @param \AppBundle\Entity\Parties $users1
     */
    public function removeUsers1(\AppBundle\Entity\Parties $users1)
    {
        $this->users1->removeElement($users1);
    }

    /**
     * Get users1
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers1()
    {
        return $this->users1;
    }

    /**
     * Add users2
     *
     * @param \AppBundle\Entity\Parties $users2
     *
     * @return Parties
     */
    public function addUsers2(\AppBundle\Entity\Parties $users2)
    {
        $this->users2[] = $users2;

        return $this;
    }

    /**
     * Remove users2
     *
     * @param \AppBundle\Entity\Parties $users2
     */
    public function removeUsers2(\AppBundle\Entity\Parties $users2)
    {
        $this->users2->removeElement($users2);
    }

    /**
     * Get users2
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers2()
    {
        return $this->users2;
    }

    /**
     * Set users1
     *
     * @param \AppBundle\Entity\Users $users1
     *
     * @return Parties
     */
    public function setUsers1(\AppBundle\Entity\Users $users1 = null)
    {
        $this->users1 = $users1;

        return $this;
    }

    /**
     * Set users2
     *
     * @param \AppBundle\Entity\Users $users2
     *
     * @return Parties
     */
    public function setUsers2(\AppBundle\Entity\Users $users2 = null)
    {
        $this->users2 = $users2;

        return $this;
    }

    /**
     * Set partieTour
     *
     * @param integer $partieTour
     *
     * @return Parties
     */
    public function setPartieTour($partieTour)
    {
        $this->partieTour = $partieTour;

        return $this;
    }

    /**
     * Get partieTour
     *
     * @return integer
     */
    public function getPartieTour()
    {
        return $this->partieTour;
    }
}
