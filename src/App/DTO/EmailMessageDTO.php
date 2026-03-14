<?php

namespace App\DTO;

class EmailMessageDTO
{
    public const TYPE_NONE = 0;
    public const TYPE_COMMENT_REPLY = 1;

    /**
     * @var string
     */
    public string $subject;

    /**
     * @var string|array<string, string>
     */
    public $from;

    /**
     * @var string|array<string, string>
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

    public function getRecipientEmail(): string
    {
        if (is_array($this->to)) {
            return array_key_first($this->to);
        }

        return $this->to;
    }
}
