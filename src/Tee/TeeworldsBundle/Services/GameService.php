<?php

namespace Tee\TeeworldsBundle\Services;

use Tee\TeeworldsBundle\Commons\AbstractService;

class GameService extends AbstractService
{
	public function getBundle()
	{
		return 'TeeworldsBundle:Game';
	}

	public function getLastGame()
	{
		return $this->getRepository()->getLastGame();
	}

	public function getGameByHash( $hash )
	{
		return $this->getRepository()->getGameByHash( $hash );
	}

	public function getGame( $id )
	{
		return $this->getRepository()->find( $id );
	}

	public function getAllGame()
	{
		return $this->getRepository()->getAllGame();
	}
}

?>