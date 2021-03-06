<?php

namespace RemotePad\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RemoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RemoteRepository extends EntityRepository {

    /*
    * get remotes by page
    */
    public function getRemotesByPage($page, $rowPerPage) {

        $to = ($page * $rowPerPage);
        $offset = $to - $rowPerPage;

        $query = $this->createQueryBuilder('r');

        $query = $query->getQuery()
                ->setFirstResult($offset)
                ->setMaxResults($rowPerPage);

        return $query->getResult();
    }

    /*
    * count remotes
    */
    public function countRemotes($userId = null) {

        $query = $this->createQueryBuilder('r')
                ->select('COUNT(r.id)');

        if ($userId != null) {

            $query->where('r.users = :userId')
                    ->setParameter('userId', $userId);
        }
        return $query->getQuery()->getSingleScalarResult();
    }

}