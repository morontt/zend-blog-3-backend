<?php

namespace App\Entity;

use App\DTO\EmailMessageDTO;
use App\Entity\Traits\ModifyEntityTrait;
use App\Repository\EmailSubscriptionSettingsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'subscription_settings')]
#[ORM\UniqueConstraint(columns: ['email', 'subs_type'])]
#[ORM\Entity(repositoryClass: EmailSubscriptionSettingsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class EmailSubscriptionSettings
{
    use ModifyEntityTrait;

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    private $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 64)]
    private $email;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private $blockSending = false;

    /**
     * @var int
     */
    #[ORM\Column(type: 'smallint', name: 'subs_type', options: [
        'default' => 1,
        'comment' => '0: none, 1: reply, 2: system',
    ])]
    private $type = EmailMessageDTO::TYPE_NONE;

    public function __construct()
    {
        $this->timeCreated = new DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
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
