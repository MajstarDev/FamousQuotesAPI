<?php

namespace App\Repository;

use App\Entity\Quote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Quote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quote[]    findAll()
 * @method Quote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteRepository extends ServiceEntityRepository
{
	public function __construct(RegistryInterface $registry)
	{
		parent::__construct($registry, Quote::class);
	}

	public function getAll($user)
	{
		return $this->createQueryBuilder('q')
		->select(array('q.id', 'a.name', 'q.text'))
		->innerJoin('q.author', 'a')
		->andWhere('q.user = :user')
		->setParameter('user', $user)
		->getQuery()
		->getArrayResult();
	}

	public function getQuoteById($user, $id)
	{
		return $this->createQueryBuilder('q')
		->select(array('q.id', 'a.name', 'q.text'))
		->innerJoin('q.author', 'a')
		->andWhere('q.user = :user')
		->setParameter('user', $user)
		->andWhere('q.id = :id')
		->setParameter('id', $id)
		->getQuery()
		->getArrayResult();
	}

	public function getRandomQuote($user)
	{
		$count = $this->createQueryBuilder('q')
		->select('COUNT(q)')
		->innerJoin('q.author', 'a')
		->andWhere('q.user = :user')
		->setParameter('user', $user)
		->getQuery()
		->getSingleScalarResult();

		return $this->createQueryBuilder('q')
		->select(array('q.id', 'a.name', 'q.text'))
		->innerJoin('q.author', 'a')
		->where('q.user = :user')
		->setParameter('user', $user)
		->setFirstResult(rand(0, $count - 1))
		->setMaxResults(1)
		->getQuery()
		->getArrayResult();
	}

    // /**
    //  * @return Quote[] Returns an array of Quote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Quote
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
