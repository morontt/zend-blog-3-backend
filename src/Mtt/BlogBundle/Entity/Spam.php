<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="spam")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\SpamRepository")
 */
class Spam
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="post_data", type="text")
     */
    protected $postData;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set postData
     *
     * @param string $postData
     * @return Spam
     */
    public function setPostData($postData)
    {
        $this->postData = $postData;
    
        return $this;
    }

    /**
     * Get postData
     *
     * @return string 
     */
    public function getPostData()
    {
        return $this->postData;
    }
}