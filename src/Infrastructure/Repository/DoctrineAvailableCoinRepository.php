<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\AvailableCoin\Entity\AvailableCoin;
use App\Domain\Model\AvailableCoin\Repository\AvailableCoinRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AvailableCoin|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvailableCoin|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvailableCoin[]    findAll()
 * @method AvailableCoin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrineAvailableCoinRepository extends ServiceEntityRepository implements AvailableCoinRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AvailableCoin::class);
    }

    public function add(AvailableCoin $availableCoin, bool $flush = true): void
    {
        $this->_em->persist($availableCoin);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function save(AvailableCoin $availableCoin, bool $flush = true): void
    {
        $this->add($availableCoin, $flush);
    }

    public function increaseStock(float $coinValue): void
    {
        $availableCoin = $this->findOneBy(["coinValue" => $coinValue]);
        $availableCoin->increaseStock();
        $this->save($availableCoin);
    }

    public function increaseCurrentlyInserted(float $coinValue): void
    {
        $availableCoin = $this->findOneBy(["coinValue" => $coinValue]);
        $availableCoin->increaseCurrentlyInserted();
        $this->save($availableCoin);
    }
}
