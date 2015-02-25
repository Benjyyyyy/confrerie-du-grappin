<?php

namespace Tee\TeeworldsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Weapon
 *
 * @ORM\Table(name="tw_weapon")
 * @ORM\Entity(repositoryClass="Tee\TeeworldsBundle\Entity\WeaponRepository")
 */
class Weapon
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="frag", type="integer")
     */
    private $frag;

    /**
     * @var Game $game
     * @ORM\ManyToOne(targetEntity="Tee\TeeworldsBundle\Entity\Game", fetch="EAGER")
     * @ORM\JoinColumn(name="game", referencedColumnName="id")
     */
    private $game;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Weapon
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
     * Set frag
     *
     * @param integer $frag
     * @return Weapon
     */
    public function setFrag($frag)
    {
        $this->frag = $frag;

        return $this;
    }

    /**
     * Get frag
     *
     * @return integer 
     */
    public function getFrag()
    {
        return $this->frag;
    }

    /**
     * Set game
     *
     * @param Game $game
     * @return Weapon
     */
    public function setGame($game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return Game $game
     */
    public function getGame()
    {
        return $this->game;
    }
}
