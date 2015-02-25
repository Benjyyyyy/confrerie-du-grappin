<?php 
namespace Tee\TeeworldsBundle\Commons;

use Doctrine\ORM\EntityManager;

abstract class AbstractService
{
	protected $em;
	
	/**
	 * 
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em) {
		$this->em = $em;
	}
	
	public function getRepository() {
		return $this->em->getRepository( $this->getBundle() );
	}
	
	public abstract function getBundle();
	
	public function save($entity) {
		$this->em->persist($entity);
		$this->em->flush();
		return $entity;
	}
	
	public function delete($entity) {
		$this->em->remove($entity);
		$this->em->flush();
	}
	
	public function update($entity) {
		$this->em->persist($entity);
		$this->em->flush();
		return $entity;
	}
}
?>