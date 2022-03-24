<?php

declare(strict_types=1);

namespace SevenInteractive\Model\Repository;

use Doctrine\ORM\EntityRepository;
use SevenInteractive\Model\Entity\Day;

abstract class DayRepository extends EntityRepository
{

    /**
     * @return Day[]
     */
    public function findForDay(\DateTime $dateTime): array
    {
        $midnight = clone $dateTime;
        $midnight->setTime(0, 0);
        $secondBeforeMidnightNextDay = clone $midnight;
        $secondBeforeMidnightNextDay->setTime(23, 59, 59);

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('d')
            ->from(Day::class, 'd')
            ->where('d.date BETWEEN :midnight AND :secondBeforeMidnightNextDay')
            ->setParameters([
                'midnight' => $midnight,
                'secondBeforeMidnightNextDay' => $secondBeforeMidnightNextDay
            ])
            ->orderBy('d.name ACS');

        return $qb->getQuery()->getResult();
    }

}