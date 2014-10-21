<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="posts_counts")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\PostCountRepository")
 */
class PostCount
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
     * @var \Mtt\BlogBundle\Entity\Post
     *
     * @ORM\OneToOne(targetEntity="Post", inversedBy="postCount")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $comments = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    protected $views = 0;


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
     * Set comments
     *
     * @param integer $comments
     * @return PostCount
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return integer
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set views
     *
     * @param integer $views
     * @return PostCount
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * Set post
     *
     * @param \Mtt\BlogBundle\Entity\Post $post
     * @return PostCount
     */
    public function setPost(\Mtt\BlogBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Mtt\BlogBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
