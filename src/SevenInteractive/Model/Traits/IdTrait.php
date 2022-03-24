<?php

declare(strict_types=1);

namespace SevenInteractive\Model\Traits;

trait IdTrait
{
    /**
     * @var int|null
     * @ORM\Column(type="integer", nullable=FALSE)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
