<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Utils\RuTransform;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * TagRepository
 *
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 */
class TagRepository extends ServiceEntityRepository
{
    use ListQueryTrait;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    /**
     * @param $term
     *
     * @return Tag[]
     */
    public function getForAutocomplete($term): array
    {
        $qb = $this->createQueryBuilder('t');

        if ($term) {
            $qb
                ->andWhere($qb->expr()->like('t.name', ':name'))
                ->setParameter('name', '%' . $term . '%')
            ;
        }

        $qb
            ->orderBy('t.name')
            ->setMaxResults(10)
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $name
     *
     * @return Tag|null
     */
    public function getTagForPost($name): ?Tag
    {
        $tag = $this->findOneBy(['name' => $name]);

        if (!$tag) {
            $tag = $this->findOneBy(['url' => RuTransform::ruTransform($name)]);
        }

        return $tag;
    }
}
