<?php

declare(strict_types=1);

namespace SevenInteractive\Model\Entity;

use Doctrine\ORM\Mapping as ORM;
use SevenInteractive\Model\Traits\IdTrait;

/**
 * @ORM\Entity(repositoryClass="SevenInteractive\Model\Repository\DayRepository")
 */
abstract class Day extends BaseEntity
{

    use IdTrait;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var \DateTime
     */
    protected $date;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Day
     */
    public function setName(string $name): Day
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return Day
     */
    public function setDate(\DateTime $date): Day
    {
        $this->date = $date;
        return $this;
    }
}