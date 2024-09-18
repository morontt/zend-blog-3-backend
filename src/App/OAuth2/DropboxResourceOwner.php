<?php
/**
 * Created by PhpStorm.
 * User: morontt
 * Date: 05.10.17
 * Time: 23:01
 */

namespace App\OAuth2;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class DropboxResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * Raw response
     *
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     *
     * @param array $response
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * Get resource owner id
     *
     * @return string
     */
    public function getId()
    {
        return $this->getValueByKey($this->response, 'account_id');
    }

    /**
     * Get resource owner name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getValueByKey($this->response, 'name.display_name');
    }

    /**
     * Return all of the owner details available as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
