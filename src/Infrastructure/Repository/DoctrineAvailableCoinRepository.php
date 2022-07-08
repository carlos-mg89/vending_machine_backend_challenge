<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\AvailableCoin\Entity\AvailableCoin;
use App\Domain\Model\AvailableCoin\Exception\CoinValueNotFound;
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

    /**
     * @throws CoinValueNotFound
     */
    public function increaseStock(float $coinValue): void
    {
        $availableCoin = $this->findOneBy(["coinValue" => $coinValue]);

        if (null == $availableCoin) {
            throw new CoinValueNotFound($coinValue);
        }

        $availableCoin->increaseStock();
        $this->save($availableCoin);
    }

    /**
     * @throws CoinValueNotFound
     */
    public function increaseCurrentlyInserted(float $coinValue): void
    {
        $availableCoin = $this->findOneBy(["coinValue" => $coinValue]);

        if (null == $availableCoin) {
            throw new CoinValueNotFound($coinValue);
        }

        $availableCoin->increaseCurrentlyInserted();
        $this->save($availableCoin);
    }

    /**
     * @return AvailableCoin[]
     */
    public function getAllCurrentlyInserted(): array
    {
        return $this->createQueryBuilder('ac')
            ->where("ac.coinCurrentlyInserted > 0")
            ->getQuery()
            ->getResult();
    }

    public function resetAllCurrentlyInsertedAndDecreaseStock(): void
    {
        $this->_em->createQueryBuilder()
            ->update(AvailableCoin::class, "ac")
            ->set("ac.coinStock", "ac.coinStock - ac.coinCurrentlyInserted")
            ->set("ac.coinCurrentlyInserted", 0)
            ->where("ac.coinCurrentlyInserted > 0")
            ->getQuery()
            ->getResult();
    }
}
