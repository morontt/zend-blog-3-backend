<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="v_comments")
 * @ORM\Entity(readOnly=true, repositoryClass="Mtt\BlogBundle\Entity\Repository\ViewCommentRepository")
 */
class ViewComment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ViewComment", mappedBy="parent")
     **/
    protected $children;

    /**
     * @ORM\ManyToOne(targetEntity="ViewComment", inversedBy="children")
     * @ORM\JoinColumn()
     **/
    protected $parent;

    /**
     * @var \Mtt\BlogBundle\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Post")
     */
    protected $post;

    /**
     * @var string
     *
     * @ORM\Column(name="uid", type="integer")
     */
    protected $virtualUserId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=80)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=80, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=160, nullable=true)
     */
    protected $website;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_addr", type="string", length=15, nullable=true)
     */
    protected $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    protected $region;

    /**
     * Country name based on ISO 3166.
     *
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=64)
     */
    protected $country;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    protected $emailHash;

    /**
     * @var int
     *
     * @ORM\Column(type="bigint", nullable=true)
     */
    protected $disqusId;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $deleted;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $timeCreated;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->timeCreated = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get virtualUserId
     *
     * @return int
     */
    public function getVirtualUserId()
    {
        return $this->virtualUserId;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Get emailHash
     *
     * @return string
     */
    public function getEmailHash()
    {
        return $this->emailHash;
    }

    /**
     * Get disqusId
     *
     * @return int
     */
    public function getDisqusId()
    {
        return $this->disqusId;
    }

    /**
     * Is deleted
     *
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Get timeCreated
     *
     * @return \DateTime
     */
    public function getTimeCreated()
    {
        return $this->timeCreated;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Get parent
     *
     * @return ViewComment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get post
     *
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
