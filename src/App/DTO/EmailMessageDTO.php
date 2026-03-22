<?php

namespace App\DTO;

class EmailMessageDTO
{
    public const TYPE_NONE = 0;
    public const TYPE_COMMENT_REPLY = 1;
    public const TYPE_SYSTEM = 2;

    /**
     * @var string
     */
    public string $subject;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
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
     * @var int
     */
    public $type = self::TYPE_NONE;

    /**
     * @var string|null
     */
    public $unsubscribeLink;

    public function __serialize(): array
    {
        return [
            $this->subject,
            $this->from,
            $this->to,
            $this->type,
            $this->unsubscribeLink,
            $this->messageText,
            $this->messageHtml,
        ];
    }

    /**
     * @phpstan-ignore missingType.iterableValue
     */
    public function __unserialize(array $data)
    {
        [
            $this->subject,
            $this->from,
            $this->to,
            $this->type,
            $this->unsubscribeLink,
            $this->messageText,
            $this->messageHtml,
        ] = $data;
    }
}
