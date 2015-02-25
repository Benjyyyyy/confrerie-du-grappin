<?php

namespace Tee\TeeworldsBundle\Services;

use Tee\TeeworldsBundle\Commons\AbstractService;

class PlayerService extends AbstractService
{
	public function getBundle()
	{
		return 'TeeworldsBundle:Player';
	}

	public function findAll()
	{
		return $this->getRepository()->findAll();
	}
}

?>