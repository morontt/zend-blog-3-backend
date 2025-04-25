<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sys_parameters")
 *
 * @ORM\Entity(repositoryClass="App\Repository\SystemParametersRepository")
 */
class SystemParameters
{
    public const DROPBOX_TOKEN = 'dropbox_token';
    public const UPDATE_VIEW_COUNTS_FROM = 'upd_view_counts_from';
    public const UPDATE_GEOLOCATION_FROM = 'upd_geolocation_from';
    public const ERRORS_5XX_CHECK = 'errors_5xx_check';
    public const TELEGRAM_UPDATES_CHECK = 'tg_updates_check';
    public const UPDATE_VIEW_COUNTS_DATA = 'upd_view_counts_data';

    /**
     * @var int
     *
     * @ORM\Id
     *
     * @ORM\Column(type="integer")
     *
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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $encrypted = false;

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
     * Set optionKey
     *
     * @param string $optionKey
     *
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
     *
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

    /**
     * Set encrypted
     *
     * @param bool $encrypted
     *
     * @return SystemParameters
     */
    public function setEncrypted(bool $encrypted)
    {
        $this->encrypted = $encrypted;

        return $this;
    }

    /**
     * Is encrypted
     *
     * @return bool
     */
    public function isEncrypted(): bool
    {
        return $this->encrypted;
    }
}
