<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait ModifyEntityTrait
{
    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt", options={"default": "CURRENT_TIMESTAMP(3)"})
     */
    protected $timeCreated;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt", options={"default": "CURRENT_TIMESTAMP(3)"})
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
     * @return DateTime
     */
    public function getLastUpdate(): DateTime
    {
        return $this->lastUpdate;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->timeCreated) {
            $this->timeCreated = new DateTime();
        }

        $this->lastUpdate = new DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->lastUpdate = new DateTime();
    }
}
