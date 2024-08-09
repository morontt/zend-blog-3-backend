<?php

namespace Mtt\UserBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *   @ORM\UniqueConstraint(columns={"external_id", "data_provider"})
 * })
 * @ORM\Entity(repositoryClass="Mtt\UserBundle\Entity\Repository\UserExtraInfoRepository")
 */
class UserExtraInfo
{
    public const MALE = 1;
    public const FEMALE = 2;
    public const UNKNOWN = 3;

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
    private $externalId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=16)
     */
    private $dataProvider;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Mtt\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $displayName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $firstName;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $lastName;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default": 1, "comment":"1: male, 2: female, 3: n/a"})
     */
    private $gender = self::UNKNOWN;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535)
     */
    private $rawData;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="milliseconds_dt", options={"default": "CURRENT_TIMESTAMP(3)"})
     */
    private $timeCreated;

    public function __construct()
    {
        $timeCreated = new DateTime();
    }
}
