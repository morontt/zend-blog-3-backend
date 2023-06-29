<?php

namespace Mtt\BlogBundle\DTO;

use Serializable;

class EmailMessageDTO implements Serializable
{
    /**
     * @var string
     */
    public string $subject;

    /**
     * @var string|array
     */
    public $from;

    /**
     * @var string|array
     */
    public $to;

    /**
     * @var string|null
     */
    public ?string $messageText;

    /**
     * @var string|null
     */
    public ?string $messageHtml;

    /**
     * @return string|null
     */
    public function serialize()
    {
        return serialize([
            $this->subject,
            $this->from,
            $this->to,
            $this->messageText,
            $this->messageHtml,
        ]);
    }

    /**
     * @param $data
     */
    public function unserialize($data)
    {
        list(
            $this->subject,
            $this->from,
            $this->to,
            $this->messageText,
            $this->messageHtml
            ) = unserialize($data);
    }
}
