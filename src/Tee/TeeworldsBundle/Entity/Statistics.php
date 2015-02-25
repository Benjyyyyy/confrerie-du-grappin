<?php

namespace Tee\TeeworldsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statistics
 *
 * @ORM\Table(name="tw_statictics")
 * @ORM\Entity(repositoryClass="Tee\TeeworldsBundle\Entity\StatisticsRepository")
 */
class Statistics
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
     * @var integer
     *
     * @ORM\Column(name="frag", type="integer",nullable=true , options={"default" : 0})
     */
    private $frag = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="death", type="integer",nullable=true , options={"default" : 0})
     */
    private $death = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="suicide", type="integer",nullable=true , options={"default" : 0})
     */
    private $suicide = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="weapon_suicide", type="integer",nullable=true , options={"default" : 0})
     */
    private $weaponSuicide = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="teamkill", type="integer",nullable=true , options={"default" : 0})
     */
    private $teamkill = 0;

    /**
     * @var Game $game
     * @ORM\ManyToOne(targetEntity="Tee\TeeworldsBundle\Entity\Game", fetch="EAGER")
     * @ORM\JoinColumn(name="game", referencedColumnName="id")
     */
    private $game;

    /**
     * @var Player $player
     * @ORM\ManyToOne(targetEntity="Tee\TeeworldsBundle\Entity\Player", fetch="EAGER")
     * @ORM\JoinColumn(name="player", referencedColumnName="id")
     */
    private $player;


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
     * Set frag
     *
     * @param integer $frag
     * @return Statistics
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
     * Set death
     *
     * @param integer $death
     * @return Statistics
     */
    public function setDeath($death)
    {
        $this->death = $death;

        return $this;
    }

    /**
     * Get death
     *
     * @return integer 
     */
    public function getDeath()
    {
        return $this->death;
    }

    /**
     * Set suicide
     *
     * @param integer $suicide
     * @return Statistics
     */
    public function setSuicide($suicide)
    {
        $this->suicide = $suicide;

        return $this;
    }

    /**
     * Get suicide
     *
     * @return integer 
     */
    public function getSuicide()
    {
        return $this->suicide;
    }

    /**
     * Set weaponSuicide
     *
     * @param integer $weaponSuicide
     * @return Statistics
     */
    public function setWeaponSuicide($weaponSuicide)
    {
        $this->weaponSuicide = $weaponSuicide;

        return $this;
    }

    /**
     * Get weaponSuicide
     *
     * @return integer 
     */
    public function getWeaponSuicide()
    {
        return $this->weaponSuicide;
    }

    /**
     * Set teamkill
     *
     * @param integer $teamkill
     * @return Statistics
     */
    public function setTeamkill($teamkill)
    {
        $this->teamkill = $teamkill;

        return $this;
    }

    /**
     * Get teamkill
     *
     * @return integer 
     */
    public function getTeamkill()
    {
        return $this->teamkill;
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

    /**
     * Set player
     *
     * @param Player $player
     * @return Weapon
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return Player $player
     */
    public function getPlayer()
    {
        return $this->player;
    }
    
    /**
     * Get ratio
     *
     * @return int $ratio
     */
    public function getRatio()
    {
        if( $this->death == 0 )
            return 1000;
        return round( $this->frag/$this->death, 2);
    }
}
?>