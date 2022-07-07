<?php

namespace App\Infrastructure\Repository;

use App\Domain\Model\PurchasableItem\Entity\PurchasableItem;
use App\Domain\Model\PurchasableItem\Repository\PurchasableItemRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PurchasableItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchasableItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchasableItem[]    findAll()
 * @method PurchasableItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoctrinePurchasableItemRepository extends ServiceEntityRepository implements PurchasableItemRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PurchasableItem::class);
    }

    public function add(PurchasableItem $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function save(PurchasableItem $entity, bool $flush = true): void
    {
        $this->add($entity, $flush);
    }

    public function remove(PurchasableItem $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
