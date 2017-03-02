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
     * Many Users have Many Groups.
     * @ManyToMany(targetEntity="Users", inversedBy="parties")
     * @JoinTable(name="users_parties")
     */
    private $users;

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="partie_joueur_1", type="smallint")
     */
    private $partieJoueur1;

    /**
     * @var int
     *
     * @ORM\Column(name="partie_joueur_1_score", type="smallint")
     */
    private $partieJoueur1Score;

    /**
     * @var int
     *
     * @ORM\Column(name="partie_joueur_2", type="smallint")
     */
    private $partieJoueur2;

    /**
     * @var int
     *
     * @ORM\Column(name="partie_joueur_2_score", type="smallint")
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
}

