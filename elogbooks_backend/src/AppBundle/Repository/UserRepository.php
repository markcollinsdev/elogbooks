<?php
/**
 * Created by PhpStorm.
 * User: octoplod
 * Date: 13/04/2019
 * Time: 12:52
 */

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Form\FilterType\Model\UserFilter;

class UserRepository extends EntityRepository
{
    /**
     * @param UserFilter $listFilterModel
     *
     * @return QueryBuilder
     */
    public function filterAndReturnQuery(UserFilter $listFilterModel)
    {
        $qb = $this->createQueryBuilder('j')
            ->setMaxResults(UserFilter::LIMIT)
        ;

        $this->applyFilter($qb, $listFilterModel);

        return $qb->getQuery();
    }

    /**
     * @param QueryBuilder $qb
     * @param ListFilter   $listFilterModel
     *
     * @return $this
     */
    public function applyFilter(QueryBuilder $qb, UserFilter $listFilterModel)
    {

        if ($listFilterModel->getOrderKey()) {
            $qb->orderBy(
                sprintf('j.%s', $listFilterModel->getOrderKey()),
                $listFilterModel->getOrderDirection()
            );
        }

        if($listFilterModel->getPriority())
        {
            $qb
                ->andWhere(sprintf(" j.priority LIKE '%s' ", "%".$listFilterModel->getPriority()."%"));
        }

        if($listFilterModel->getName())
        {
            $qb
                ->andWhere(sprintf(" j.name = %d ", $listFilterModel->getName()));
        }

        if($listFilterModel->getStatus())
        {
            $qb
                ->andWhere(sprintf(" j.status = '%s' ", $listFilterModel->getStatus()));
        }
        if($listFilterModel->getType())
        {
            $qb
                ->andWhere(sprintf("j.type = '%s' ",$listFilterModel->getType()));
        }

        if($listFilterModel->getEmail())
        {
            $qb
                ->andWhere(sprintf("j.email LIKE '%s'","%".$listFilterModel->getEmail()."%"));
        }

    }

}