<?php
declare(strict_types=1);

namespace app\System\Service;

use DI\DependencyException;
use DI\NotFoundException;
use Dux\App;
use Dux\Validator\Data;
use Enqueue\Consumption\ChainExtension;
use Enqueue\Consumption\Extension\LimitConsumedMessagesExtension;
use Enqueue\Consumption\Extension\LimitConsumptionTimeExtension;
use Enqueue\Consumption\Extension\SignalExtension;
use Enqueue\Consumption\QueueConsumer;
use Interop\Queue\Exception;
use Interop\Queue\Exception\InvalidDestinationException;
use Interop\Queue\Exception\InvalidMessageException;

class Notify {

    /**
     * 消费主题
     * @param string $label 消息标志
     * @return array
     */
    public static function consume(string $label): array {
        $context = App::queue()->context;
        $queueConsumer = new QueueConsumer($context, new ChainExtension([
            new LimitConsumptionTimeExtension(new \DateTime('now + 3 sec')),
            new LimitConsumedMessagesExtension(1)
        ]));

        $processor = new NotifyProcessor();
        $queueConsumer->bind("notify:$label", $processor);
        $queueConsumer->consume();
        return $processor->get();
    }

    /**
     * 发送主题
     * @param string $label  消息标志
     * @param string $type 消息类型
     * @param array $params 消息参数
     * @return void
     */
    public static function send(string $label, string $type, array $params = []): void
    {
        $context = App::queue()->context;
        $fooTopic = $context->createTopic("notify:$label");
        $data = [
          'type' => $type,
          'params' => $params
        ];
        $message = $context->createMessage(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $context->createProducer()->send($fooTopic, $message);
    }

}