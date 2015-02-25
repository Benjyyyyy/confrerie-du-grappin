<?php

namespace Tee\TeeworldsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Tee\TeeworldsBundle\Commons\AbstractController;
use Tee\TeeworldsBundle\Utils\GamesUtils;
use Tee\TeeworldsBundle\Utils\StringUtils;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect( $this->generateUrl("stats") );
    }

    /**
     * @Route("/stats/{id}", name="stats", defaults={"id" = "last"})
     * @Template()
     */
    public function statsAction( $id )
    {
        $game = null;

    	if( $id != "last")
            $game = $this->getServiceGame()->getGame( $id );

        if( $game == null )
            $game = $this->getServiceGame()->getLastGame();

        $statistics = $this->getServiceStatistics()->getStatisticsByGame( $game );

        $navGame = array();
        foreach( $this->getServiceGame()->getAllGame() as $g )
        {
            if( $g->getId() != $game->getId() )
                $navGame[ $this->generateUrl( 'stats', array( 'id' => $g->getId() )) ] = $g->getDate()->format("d/m/Y");
        }

        return array('game' => $game->getDate()->format("d/m/Y"),
        				'kill' => GamesUtils::getData( $statistics, 'kill', 'getFrag'),
						'death' => GamesUtils::getData( $statistics, 'death', 'getDeath'),
						'weaponSuicide' => GamesUtils::getData( $statistics, 'weaponSuicide', 'getWeaponSuicide'),
						'suicide' => GamesUtils::getData( $statistics, 'suicide', 'getSuicide'),
						'teamkill' => GamesUtils::getData( $statistics, 'teamKill', 'getTeamKill'),
						'ratio' => GamesUtils::getData( $statistics, 'ratio', 'getRatio'),
                        'weapon' => GamesUtils::getDataWeapon( $this->getServiceWeapon()->getWeaponByGame( $game ), 'weapon'),
                        'menu' => GamesUtils::$titles,
                        'navGame' => $navGame
						);
						
						
						
    }

    /**
     * @Route("/total/stats", name="totalStats")
     * @Template()
     */
    public function totalStatsAction()
    {
         $statistics = $this->getServiceStatistics()->getTotalStatistics();
         return array('kill' => GamesUtils::getTotalData( $statistics, 'kill'),
                        'death' => GamesUtils::getTotalData( $statistics, 'death'),
                        'weaponSuicide' => GamesUtils::getTotalData( $statistics, 'weaponSuicide'),
                        'suicide' => GamesUtils::getTotalData( $statistics, 'suicide'),
                        'teamkill' => GamesUtils::getTotalData( $statistics, 'teamKill'),
                        'ratio' => GamesUtils::getTotalData( $statistics, 'ratio'),
                        'weapon' => GamesUtils::getTotalDataWeapon( $this->getServiceWeapon()->getTotalWeapon(), 'weapon'),
                        'menu' => GamesUtils::$titles
                        );
    }

    /**
     * @Route("/update", name="update")
     * @Template()
     */
    public function updateAction()
    {
        $nb = GamesUtils::parseFolder( $this );
        return array( "menu" => array(), "nb" => $nb );
    }
}
