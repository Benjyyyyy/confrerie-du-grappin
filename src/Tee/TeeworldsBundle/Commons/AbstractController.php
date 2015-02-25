<?php
namespace Tee\TeeworldsBundle\Commons;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use chapoton\CoreBundle\Utils\StringUtils;

class AbstractController extends Controller
{	
	public function getServiceGame() {
    	return $this->get("teeworlds.service.game");
    }
	
	public function getServicePlayer() {
    	return $this->get("teeworlds.service.player");
    }
	
	public function getServiceStatistics() {
    	return $this->get("teeworlds.service.statistics");
    }
	
	public function getServiceWeapon() {
    	return $this->get("teeworlds.service.weapon");
    }
}
