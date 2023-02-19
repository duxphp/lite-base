<?php

namespace App\System\Service;

use Dux\Validator\Data;
use Interop\Queue\Processor;
use Interop\Queue\Message;
use Interop\Queue\Context;

class NotifyProcessor implements Processor
{

    public array $data = [];

    public function __construct()
    {
    }

    public function process(Message $message, Context $context): object|string
    {
        $this->data = json_decode($message->getBody(), true);
        return self::ACK;
    }

    public function get(): array
    {
        return $this->data;
    }
}