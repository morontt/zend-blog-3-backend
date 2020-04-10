<?php

namespace Mtt\BlogBundle\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait ModifyEntityTrait
{
    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt")
     */
    protected $timeCreated;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt", nullable=true)
     */
    protected $lastUpdate;

    /**
     * @param DateTime $timeCreated
     *
     * @return $this
     */
    public function setTimeCreated(DateTime $timeCreated)
    {
        $this->timeCreated = $timeCreated;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTimeCreated(): DateTime
    {
        return $this->timeCreated;
    }

    /**
     * @param DateTime $lastUpdate
     *
     * @return $this
     */
    public function setLastUpdate(DateTime $lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLastUpdate(): ?DateTime
    {
        return $this->lastUpdate;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->timeCreated) {
            $this->timeCreated = new DatetIme();
        }

        $this->lastUpdate = new DatetIme();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->lastUpdate = new DatetIme();
    }
}
