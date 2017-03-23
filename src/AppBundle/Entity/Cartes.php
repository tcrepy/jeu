<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cartes
 *
 * @ORM\Table(name="cartes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartesRepository")
 */
class Cartes
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    // Liaison avec la table Modeles
    /**
     * Many Cartes have One Modeles.
     * @ORM\ManyToOne(targetEntity="Modeles")
     */
    private $modeles;

    //liaison avec la table Parties
    /**
     * Many Cartes have One Parties
     * @ORM\ManyToOne(targetEntity="Parties")
     */
    private $parties;

    /**
     * @var string
     *
     * @ORM\Column(name="carte_situation", type="string", length=255)
     */
    private $carteSituation;

    /**
     * @var int
     *
     * @ORM\Column(name="carte_ordre", type="smallint")
     */
    private $carteOrdre;


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
     * Set carteSituation
     *
     * @param string $carteSituation
     *
     * @return Cartes
     */
    public function setCarteSituation($carteSituation)
    {
        $this->carteSituation = $carteSituation;

        return $this;
    }

    /**
     * Get carteSituation
     *
     * @return string
     */
    public function getCarteSituation()
    {
        return $this->carteSituation;
    }

    /**
     * Set carteOrdre
     *
     * @param integer $carteOrdre
     *
     * @return Cartes
     */
    public function setCarteOrdre($carteOrdre)
    {
        $this->carteOrdre = $carteOrdre;

        return $this;
    }

    /**
     * Get carteOrdre
     *
     * @return int
     */
    public function getCarteOrdre()
    {
        return $this->carteOrdre;
    }

    /**
     * Set modeles
     *
     * @param \AppBundle\Entity\Modeles $modeles
     *
     * @return Cartes
     */
    public function setModeles(\AppBundle\Entity\Modeles $modeles = null)
    {
        $this->modeles = $modeles;

        return $this;
    }

    /**
     * Get modeles
     *
     * @return \AppBundle\Entity\Modeles
     */
    public function getModeles()
    {
        return $this->modeles;
    }

    /**
     * Set parties
     *
     * @param \AppBundle\Entity\Parties $parties
     *
     * @return Cartes
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
}
