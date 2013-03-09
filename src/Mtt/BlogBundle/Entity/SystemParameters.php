<?php

namespace Mtt\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sys_parameters")
 * @ORM\Entity(repositoryClass="Mtt\BlogBundle\Entity\Repository\SystemParametersRepository")
 */
class SystemParameters
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
     * @ORM\Column(name="optionkey", length=128, unique=true)
     */
    protected $optionKey;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $value;

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
     * Set optionKey
     *
     * @param string $optionKey
     * @return SystemParameters
     */
    public function setOptionKey($optionKey)
    {
        $this->optionKey = $optionKey;
    
        return $this;
    }

    /**
     * Get optionKey
     *
     * @return string 
     */
    public function getOptionKey()
    {
        return $this->optionKey;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return SystemParameters
     */
    public function setValue($value)
    {
        $this->value = $value;
    
        return $this;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
}
