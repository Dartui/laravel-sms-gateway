<?php

namespace Dartui\SmsGateway\Notification;

use Carbon\Carbon;

class Message
{
    /**
     * Recipient phone number.
     *
     * @var integer|string
     */
    public $number;

    /**
     * SMS content.
     *
     * @var string
     */
    public $content;

    /**
     * Time to send the message.
     *
     * @var integer
     */
    public $sendAt;

    /**
     * Time to give up trying to send the message.
     *
     * @var integer
     */
    public $expiresAt;

    /**
     * Create a new message instance.
     *
     * @param  string $content
     * @return void
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Set the recipient phone number.
     *
     * @param  integer|string $number
     * @return $this
     */
    public function number($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Set the SMS content.
     *
     * @param  string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set time to send the message.
     *
     * @param  \Carbon\Carbon|integer|string $time
     * @return $this
     */
    public function sendAt($time)
    {
        $this->sendAt = $this->getTimestamp($time);

        return $this;
    }

    /**
     * Set time to give up trying to send the message.
     *
     * @param  \Carbon\Carbon|integer|string $time
     * @return $this
     */
    public function expiresAt($time)
    {
        $this->expiresAt = $this->getTimestamp($time);

        return $this;
    }

    /**
     * Array representation of message.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'number'     => $this->number,
            'message'    => $this->content,
            'send_at'    => $this->sendAt,
            'expires_at' => $this->expiresAt,
        ];
    }

    /**
     * Get timestamp from mixed time value.
     *
     * @param  \Carbon\Carbon|integer|string $time
     * @return integer
     * @throws \Exception
     */
    protected function getTimestamp($time)
    {
        if ($time instanceof Carbon) {
            return $time->timestamp;
        }

        if (is_numeric($time)) {
            return $time;
        }

        return Carbon::parse($time)->timestamp;
    }
}
