<?php

namespace Tee\TeeworldsBundle\Services;

use Tee\TeeworldsBundle\Commons\AbstractService;

class WeaponService extends AbstractService
{
	public function getBundle()
	{
		return 'TeeworldsBundle:Weapon';
	}

	public function getWeaponByGame( $game )
	{
		return $this->getRepository()->getWeaponByGame( $game );
	}

	public function getTotalWeapon()
	{
		return $this->getRepository()->getTotalWeapon();
	}
}

?>