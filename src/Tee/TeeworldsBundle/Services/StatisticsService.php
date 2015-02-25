<?php

namespace Tee\TeeworldsBundle\Services;

use Tee\TeeworldsBundle\Commons\AbstractService;

class StatisticsService extends AbstractService
{
	public function getBundle()
	{
		return 'TeeworldsBundle:Statistics';
	}

	public function getStatisticsByGame( $game )
	{
		return $this->getRepository()->getStatisticsByGame( $game );
	}

	public function getTotalStatistics()
	{
		return $this->getRepository()->getTotalStatistics();
	}
}

?>