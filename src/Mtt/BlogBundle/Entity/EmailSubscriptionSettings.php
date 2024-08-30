<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mtt\BlogBundle\Entity\Traits\ModifyEntityTrait;

/**
 * @ORM\Table(name="subscription_settings", uniqueConstraints={
 *   @ORM\UniqueConstraint(columns={"email", "subs_type"})
 * })
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\EmailSubscriptionSettingsRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EmailSubscriptionSettings
{
    use ModifyEntityTrait;

    public const TYPE_COMMENT_REPLY = 1;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $blockSending = false;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", name="subs_type", options={"default": 1, "comment":"1: reply"})
     */
    private $type = self::TYPE_COMMENT_REPLY;

    public function __construct()
    {
        $this->timeCreated = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isBlockSending(): bool
    {
        return $this->blockSending;
    }

    public function setBlockSending(bool $block): self
    {
        $this->blockSending = $block;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
