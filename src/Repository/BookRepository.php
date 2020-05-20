<?php

namespace App\Repository;

use App\Entity\Book;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findBeforeDate(\Datetime $date)
    {
        $qb = $this->createQueryBuilder("e");
        $qb
            ->andWhere('e.published <=  :to')
            ->setParameter('to', $date)
        ;
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function saveBook(Array $book) {
        $bookObject = new Book();

        $bookObject->setIsbn($book['isbn']);
        $bookObject->settitle($book['title']);
        $bookObject->setSubtitle($book['subtitle']);
        $bookObject->setAuthor($book['author']);
        $bookObject->setPublished(new Carbon($book['published']));
        $bookObject->setPublisher($book['publisher']);
        $bookObject->setPages($book['pages']);
        $bookObject->setDescription($book['description']);
        $bookObject->setWebsite($book['website']);
        $bookObject->setCategory($book['category']);

        $this->getEntityManager()->persist($bookObject);
        $this->getEntityManager()->flush();
    }

    public function removeBook(Book $book) {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
