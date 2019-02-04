<?php

namespace App\Api\Repository\Catalog;

use App\Api\Entity\Catalog\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findAllJoined()
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.relations', 'pr')
            ->leftJoin('pr.product', 'ppr')
            ->leftJoin('p.price', 'pp')
            ->leftJoin(
                'p.priceDiscount',
                'ppd',
                'WITH',
                'ppd.product = p.id OR ppd.product IS NULL'
            )
            ->addSelect('pr')
            ->addSelect('ppr')
            ->addSelect('pp')
            ->addSelect('ppd')
            ->getQuery();

        return $query->getResult();
    }

    public function findAllArrayResult()
    {
        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.relations', 'pr')
            ->leftJoin('pr.product', 'ppr')
            ->leftJoin('p.price', 'pp')
            ->leftJoin(
                'p.priceDiscount',
                'ppd',
                'WITH',
                'ppd.product = p.id OR ppd.product IS NULL'
            )
            ->addSelect('pr')
            ->addSelect('ppr')
            ->addSelect('pp')
            ->addSelect('ppd')
            ->getQuery();

        return $query->getArrayResult();
    }
    // /**
    //  * @return ProductHydrator[] Returns an array of ProductHydrator objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductHydrator
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
